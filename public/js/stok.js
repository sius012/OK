$(document).ready(function(){
    $(".btnClosed").click(function(){
        $("#modaluploader").modal("hide");
    });
    $("#uploadbuttonstok").click(function(){
        Swal.fire({
            title: 'Apakah anda yakin ingin menambahkan',
            showDenyButton: true,
            confirmButtonText: 'Upload',
            denyButtonText: `Batal`,
          }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
              $.ajax({
                headers: {
                    "X-CSRF-TOKEN" : $("meta[name=csrf-token]").attr('content')
                },
                url: "/updateallstok",
                type: "post",
                dataType: "json",
                success: function(data){
                    Swal.fire(`${data['jumlah']} item berhasil dimasukan`,'','success');
                    window.location = "/stok";
                },error: function(err){
                    Swal.fire('sepertinya ada kesalahan','','info');
                    alert(err.responseText);
                }
              });
            } else if (result.isDenied) {
              Swal.fire('Changes are not saved', '', 'info')
            }
          })
    });
    $("#generatestok").click(function(e){
        $.ajax({
            headers: {
                "X-CSRF-TOKEN" : $("meta[name=csrf-token]").attr('content')
            },
            type: "post",
            dataType: 'json',
            url: "/loaddatastok",
            success: function(data){
                $("#modaluploader").modal('show');

                let li = data['stokless'].map(function(lis){
                    return `<li>${lis['kode_produk']} ${lis['nama_produk']}</li>`;
                });
                $("#ktless").html(li);
                $("#jp").html("<b>Jumlah Produk : </b>"+data['jumlah produk']+" item");
                $("#kat").html("<b>Produk dalam katalog : </b>"+data['tersedia dikatalog']+" item");
                $("#stoklessbutton").text(data['jumlah produk']-data['tersedia dikatalog']);
                $("#bt").html("<b>Produk diluar katalog : </b>"+(data['jumlah produk']-data['tersedia dikatalog'])+" item");
            },error: function(err){
            }   
        });
    });
    
    let timerInterval

    function hapusStok(kode_stok){
        $.ajax({
            headers:{
                "X-CSRF-TOKEN" : $("meta[name='csrf-token']").attr('content')
            },
            data: {
               kode_stok : kode_stok
            },
            url: "/hapusstok",
            type: "POST",
            success: function(data){
                Swal.fire('Permintaan diterima', '', 'success').then(function(){
                    window.location = '/stok';
                });
            },
            error: function(err){
            }
        });
    }

    function loadsinglestok(id){
        $.ajax({
            headers:{
                "X-CSRF-TOKEN" : $("meta[name='csrf-token']").attr('content')
            },
            data: {
                id: id
            },
            url: "/loadsinglestok",
            type: "POST",
            dataType: "JSON",
            success: function(data){
                $("#kodeproduk-input").val(data[0]['kode_produk']);
                $("#jumlahproduk-input").val(data[0]['jumlah']);
            },
            error: function(err){
            }
        });
    }


    $("#submitterstok").submit(function(e){
        let url = $(this).attr('action');
        e.preventDefault();

        var data = {
            'kode_produk' : $("#kodeproduk-input").val(),
            'jumlah' : $("#jumlahproduk-input").val(),
            'created_at' : $("#tgl-input").val(),
        };
        $.ajax({
            headers: {
                'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                data: data
            },
            url: url,
            type: "POST",
            dataType: "JSON",
            success: function(data){
                if(url == "/editstok"){
                    alert('data berhasil diedit');
                }else{
                    if(data['can'] == true){
                        alert('data berhasil ditambahkan');
                    }else{
                        alert('data telah tersedia');
                    }
                    window.location = "/stok"
                }
                
            },
            error: function(err){
                window.location = "/stok"
            }
        });
    });


    $("#stokfiller").on('click', '.editstok', function(e){
        e.preventDefault();
        $("#submitterstok").attr('action','/editstok');
        $("#kodeproduk-input").val("");
        $("#kodeproduk-input").attr('disabled','disabled');
        $("#tgl-input").attr('disabled','disabled');
       loadsinglestok($(e.target).attr('kode_stok') == undefined ? $(e.target).closest('button').attr('kode_stok') : $(e.target).attr('kode_stok'));
        $("#modalstok").modal('show');
        $(".tombolsubmit").text("Ubah");
 
    });


    $("#stokfiller").on('click', '.hapusstok', function(e){
        e.preventDefault();
        Swal.fire({
            title: 'Apakah anda yakin ingin menghapus',
            showDenyButton: true,
            confirmButtonText: 'Batalkan',
            denyButtonText: `Hapus`,
          }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
            } else if (result.isDenied) {
              hapusStok($(e.target).attr('kode_stok') == undefined ? $(e.target).closest("button").attr('kode_stok'): $(e.target).attr('kode_stok') );
            }
          });
 
    });






    $("button[ data-target='#modalstok'").click(function(e){
        $("#kodeproduk-input").val("");
        $("#jumlahproduk-input").val("");
        $("#kodeproduk-input").removeAttr('disabled');
        $("#tgl-input").removeAttr('disabled','disabled');
        $(".tombolsubmit").text("Tambah");
    });

    $("#stokprint").click(function(e){
    
        $.ajax({
            headers: {
                "X-CSRF-TOKEN" : $("meta[name=csrf-token]").attr('content'),
            },
            url: "/printcurrentstok",
            data:{
                'id_merek' : $("#merek").val(),
                "id_kodetype" : $("#kodetype").val(),
                "id_tipe" : $("#tipe").val()
            },
            type: "post",
            success: function(response){
             
                printJS({printable: response['filename'], type: 'pdf', base64: true});
            },error: function(err){
                alert(err.responseText);
            }
        });
    });

    $("#stokprintg").click(function(e){
    
        $.ajax({
            headers: {
                "X-CSRF-TOKEN" : $("meta[name=csrf-token]").attr('content'),
            },
            url: "/printcurrentstokgudang",
            data:{
                'id_merek' : $("#merek").val(),
                "id_kodetype" : $("#kodetype").val(),
                "id_tipe" : $("#tipe").val()
            },
            type: "post",
            success: function(response){
             
                printJS({printable: response['filename'], type: 'pdf', base64: true});
            },error: function(err){
                alert(err.responseText);
            }
        });
    });

    
});