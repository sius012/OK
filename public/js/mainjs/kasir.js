

function formatRupiah(angka, prefix) {
    var number_string = angka.replace(/[^,\d]/g, '').toString(),
        split = number_string.split(','),
        sisa = split[0].length % 3,
        rupiah = split[0].substr(0, sisa),
        ribuan = split[0].substr(sisa).match(/\d{3}/gi);

    // tambahkan titik jika yang di input sudah menjadi angka ribuan
    if (ribuan) {
        separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
    }

    rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
    return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
}




$(document).ready(function () {
   //cek dan load data surat jalan
  

    var hasRetur = $("#returid").val().length < 2 ? false : true;

    function fr(angka, prefix) {
        var number_string = angka.toString().replace(/[^,\d]/g, '').toString(),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);
    
        // tambahkan titik jika yang di input sudah menjadi angka ribuan
        if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }
    
        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        if(angka<0){
            return prefix == undefined ? "-" +rupiah : (rupiah ? "-"+rupiah : '');
        }else{
            return prefix == undefined ? rupiah : (rupiah ? rupiah : '');
        }
       
    }


    $("#ta").keyup(function(){
        $("#totality").val(fr(subtotalafterdiskon-$(this).val()));
    });


   


    var id_trans = "null";
    var id_pre = "null";

    function getIdTrans(val){
        id_trans = val;
    }


    if($("#sj-id").val() != ""){
        getIdTrans($("#sj-id").val());
        loader(id_trans,"");
    }
    



     function getIdPre(val){
        id_pre = val;
    }

    var hasfinish = false;
    if($("#jenis-transaksi").val() == "normal"){
        $(".normalt").show();
    }else{
        $(".normalt").hide();
    }

    

    $("#jenis-transaksi").change(function(){
        if($(this).val()=='normal'){
           
            window.location = "/kasir?jenis=umum";
        }else if($(this).val() == 'preorder'){
            
            window.location = "/kasir?jenis=preorder";
        }else if("suratjalan"){
            window.location = "/kasir?jenis=suratjalan";
        }
    });
    $(".alerts").hide();
    $(document).on('keypress', function (event) {
        let keycode = (event.keyCode ? event.keyCode : event.which);
        if(keycode == 17){
       
        }
        
    });


    //hider






    $(document).on('keypress', function (event) {
       
        let keycode = (event.keyCode ? event.keyCode : event.which);
        if (keycode == '13' && $("#searcher").val() != "" && !hasfinish) {

            tambahItem(
                $("#searcher").val(),
                $("#hrg").val(),
                $("#qty").val(),
                0,
                $("#jenisproduk").val().length < 1 ? "produk" : $("#jenisproduk").val()
            );


            $("#searcher").val("");
            $("#myUL").html("");
        }

        $.ajax({
            headers: function (e) {

            },
        });


            
        

    });

    $("#tambahproduk").click(function (event) {

        if(!hasfinish &&  $("#searcher").val() != ""){
            tambahItem(
                $("#searcher").val(),
                $("#hrg").val(),
                $("#qty").val(),
                0,
                $("#jenisproduk").val().length < 1 ? "produk" : $("#jenisproduk").val()
            );
        }
     

    });

    $("#next-button").attr("disabled", "disabled");
    $("#suratjalan").attr("disabled", "disabled");
    $("#suratjalan").attr("disabled", "disabled");
    $(".antartd").hide();
    $("#antarkah").change(function (e) {

        if ($(this).val() !== "antar") {
            $("#suratjalan").attr("disabled", "disabled");
            $(".antartd").hide();
        } else {
            $(".antartd").show();
            $("#suratjalan").removeAttr("disabled");
        }
    }
    );

    //loader
    function loader(a,b) {
       
        $("#tabling").hide();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                jenis: $("#jenis-transaksi").val(),
                id_trans: id_trans,
                id_pre: id_pre
            },

            type: "POST",
            dataType: "JSON",
            url: "/loader",
            success: function (data) {
              
       
                var no = 1;
                let subtotal = 0;
           

                var row = data['datadetail'].map(function (dato, i) {
                    subtotal += doDisc(dato['jumlah'], dato['harga_produk'], dato['potongan'], dato['prefix']);
                    return `
                        <tr>
                            <td>${i + 1}</td>
                            <td>${dato['kode_produk']}</td>
                            <td>${dato['nama_kodetype']+" "+dato['nama_merek']+" "+dato['nama_produk']}</td>
                            <td>${dato['jumlah']}</td>
                            <td>Rp. ${fr(parseInt(dato['harga']),"")}</td>
                            <td>${dato["prefix"] != "persen" ? fr(parseInt(dato['potongan']),"") : dato["potongan"] + "%"}</td>
                            <td> Rp. ${fr(doDisc(dato['jumlah'], dato['harga_produk'], dato['potongan'], dato['prefix']),"")}</td>
                            <td><buttton class="btn btn-danger beforesend buang" id_detail="${dato['id']}"><i class="fa fa-trash"></i></button></td>
                        </tr>
                    `

                });
        
                getIdTrans(data['datadetail'][0]['kode_trans']);
                
              
                


                $('#subtotal').val(fr(subtotal,""));

                $('#tabling').html(row);
                $("#tabling").show("slow");
              
                subtotal1 = subtotal;
                subtotalafterdiskon = doDisc(1,subtotal, $("#diskon").val().replace(/[._]/g, ''),$("#prefix").val());
                $("#totality").val(fr(subtotalafterdiskon,""));
                
                if(hasRetur==true){
              
                    subtotalafterdiskon -=  $("#ta").val();
                    $("#totality").val(fr(subtotalafterdiskon ,""));
                    
                }
               
                 

            },
            error: function (err) {
                // Swal.fire("terjadi kesalahan","","info");
                alert("terjadi kesalahan");
            }
        });
    }








    var subtotal1 = 0;
    var subtotalafterdiskon = 0;

    function perbaruisubtotal(angka){
        subtotalafterdiskon = 10
    }

    var metode = "cash";

    if (metode == "cash") {
        $("#kredit input").attr("disabled", "disabled");
    }

    // $("#kredit").click(function(){
    //     $("#kredit div input").removeAttr("disabled");
    //     $("#kredit div select").removeAttr("disabled");
    //     $("#tunai div input").attr("disabled","disabled");
    //     $("#tunai div select").attr("disabled","disabled");
    //     $(this).addClass("active");
    //      $("#tunai").removeClass("active");
    //      $("#tunai").removeClass("active");
    //      $("#kredit-input").addClass("usethis");
    //      $("#kreditvia-input").addClass("usethisvia");
    //      $("#cash-input").removeClass("usethis");
    //      $("#cashvia-input").removeClass("usethisvia");
    //      metode = "kredit";
    // });

    //  $("#tunai").click(function(){
    //     $("#tunai input").removeAttr("disabled");
    //     $("#tunai select").removeAttr("disabled");
    //     $("#kredit input").attr("disabled","disabled");
    //     $("#kredit select").attr("disabled","disabled");
    //     $(this).addClass("active");
    //     $("#kredit").removeClass("active");
    //     $("#cash-input").addClass("usethis");
    //     $("#cashvia-input").addClass("usethisvia");
    //     $("#kredit-input").removeClass("usethis");
    //     $("#kreditvia-input").removeClass("usethisvia");
    //     metode = "cash";    
    // });





    if (id_trans == 0) {
        $("#selesai").attr('disabled', 'disabled');
    }
    $("#totality").html(subtotal1);


    $(".kredit input").attr("disabled", "disabled");

    $(".drop").hide();
    $("#searcher").keyup(function () {
        $("#myUL").show();

        kw = $(this).val();


        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                data: kw
            },

            type: "POST",
            dataType: "JSON",
            url: "/cari",
            success: function (data) {
                if (data['currentproduk'] != undefined) {
                    $("#hrg").val($(data['currentproduk']['harga']));
                    $("#hrg-nominal").html(":  RP. " + fr(parseInt(data['currentproduk']['harga']),""));
                }



                $(".drop").show();
                if (data['data'].length > 0) {
                    var li = "";
                    var li2 = "";
                    for (var i = 0; i < data['data'].length; i++) {
                        li += `<li>

                                   <a jenis="produk" kode="${data['data'][i]['kode_produk']}" harga="${data['data'][i]['harga']}" jumlah="1" potongan="0" class="sear">${data['data'][i]["kode_produk"]+ " "+data['data'][i]['nama_kodetype'] +" "+ data['data'][i]['nama_merek'] + " " + data['data'][i]["nama_produk"] + " "}</a>
                                </div>
                            
                            </li>`;
                    }

                    for (var i = 0; i < data['data2'].length; i++) {
                        li += `<li>

                                   <a jenis="paket" kode="${data['data2'][i]['kode_paket']}" harga="${data['data'][i]['harga']}" jumlah="1" potongan="0" class="sear">${data['data2'][i]["kode_paket"] + " " + data['data2'][i]["nama_paket"]}</a>
                                </div>
                            
                            </li>`;
                    }

                    $("#myUL").html(li+li2);
                } else {
                    $(".drop").hide();
                }

                console.log(data.length);

            },
            error: function (err, response,) {
                Swal.fire("terjadi kesalahan", "", "info");
                alert(err.responseText);
            }
        });
    });

    $(document).click(function () {
        $("#myUL").hide();
    });

    $("#myUL").click(function (e) {
        e.stopPropagation();
    });





    function tambahItem(id, harga, jumlah, potongan,jenis) {
       
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                data: {
                    kode_produk: id,
                    harga: harga,
                    jumlah: jumlah,
                    potongan: potongan,
                    jenis_transaksi: $("#jenis-transaksi").val(),
                    jenis: jenis,
                    id_pre: id_pre,
                    id_trans: id_trans
                }
            },
            type: "POST",
            dataType: "JSON",
            url: $("#jenis-transaksi").val() == 'preorder' ? "/tambahpre" : "/tambahItem",
            success: function (data, response) {
                if($("#jenis-transaksi").val() == 'normal'){
                       getIdTrans(data['datadetail'][0]['kode_trans'] == undefined ? "null" : data['datadetail'][0]['kode_trans'] );

                loader(id_trans,id_pre);
            }else{
               
                getIdPre(data['datadetail'][0]['kode_trans']);
               
                loader(id_trans,id_pre);
            }
          
             
               




            },
            error: function (err, response, errorThrown, jqXHR) {
                alert(err.responseText);
            }
        });

        $("#selesai").removeAttr('disabled');
    }

    $("#myUL").on("click", ".sear", function (event) {  
        $("#searcher").val($(event.target).attr("kode"));
        $("#myUL").hide();
        $("#hrg").val($(event.taret).attr("harga"));
        $("#hrg-nominal").html(":  RP. " + fr(parseInt($(event.target).attr("harga")),""));
        $("#jenisproduk").val($(event.target).attr("jenis") != undefined ? $(event.target).attr("jenis") : "produk");

    });


    $("#formsubmitter").submit(function (e) {
        e.preventDefault();
        tambahItem(
            $("#searcher").val(),
            $("#hrg").val(),
            $("#qty").val(),
            0,
            $("#jenisproduk").val().length < 1 ? "produk" : $("jenisproduk").val()
        );
    });



    $(".drop").on("keyup", ".jml", function () {
        $(event.target).closest(".bungkuser").children(".sear").attr("jumlah", $(event.target).val());
    });

    $(".drop").on("keyup", ".potongan", function () {
        $(event.target).closest(".bungkuser").children(".sear").attr("potongan", $(event.target).val());
    });


    if($("#sj-checker").prop("checked") == true){
       
    }

   

    $("#selesai").click(function () {
       
        
        
  
        if(!hasfinish){
    
        
        if (($(".usethis").val() == "" || parseInt($(".usethis").val().replace(/[._]/g, '')) < parseInt($("#totality").val().replace(/[._]/g, ''))) && $("#jenis-transaksi").val() == "normal" ) {
         
            alert("uang kurang");
            alert("subtotalafter diskon anda :" +subtotalafterdiskon);
        } else {
           
           
            if ($("#nama").val() == null ||
                $("#nama").val() == "" ||
                $(".usethisvia").val() == " " ||
                $('#telp').val() == "" ||
                $("#alamat").val() == "") {
                Swal.fire("Pastikan Semua Kolom terisi(kecuali diskon)", "", "info");
            } else {
                $("#reset-button").hide();
               
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        data: {
                            nama_pelanggan: $("#nama").val(),
                            diskon: $("#diskon").val().replace(/[._]/g, ''),
                            bayar: $(".usethis").val().replace(/[._]/g, ''),
                            metode: "cash",
                            via: $(".usethisvia").val(),
                            telp: $("#telp").val(),
                            alamat: $("#alamat").val(),
                            prefix: $("#prefix").val(),

                            antarkah: $("#antarkah").val(),
                            id_trans: id_trans,
                            id_pre: id_pre,
                            notab: $("#preorderid").val(),

                            no_nota_retur: $("#no-nota-retur").val(),
                            td: $("#ta").val(),
                            keterangan_retur: $("#keterangan-retur").val(),

                            jenis_transaksi: $("#jenis-transaksi").val(),
                            fromsj: $("#sj-checker").prop("checked") == true ? 1 : 0,
                            id_sj: $("#sj-id").val(),
                        }
                    },
                    type: "POST",
                    url: $("#jenis-transaksi").val() == "normal" || $("#jenis-transaksi").val() == "suratjalan"  ? "/selesaitransaksi" : "/selesaipreorder",
                    success: function (data) {
                        $(".buang").hide();
                        hasfinish = true;
                        $("#selesai").removeClass("selesaiindi");
                        $("#selesai").addClass("printindi");
                        $("#selesai").html("<i class='fa fa-print mr-3'></i>Cetak");
                        Swal.fire(
                            'Transaksi Berhasil!',
                            '',
                            'success'
                        );
                        if($("#jenis-transaksi").val() == "normal"){
                            print(id_trans);
                        }else if($("#jenis-transaksi").val() == "suratjalan"){
                            printsuratjalan(id_trans);
                        }
                        else{
                
                            printpreorder(id_pre);
                        }
                        $("#next-button").removeAttr("disabled");
                        $(".beforesend").attr("disabled","disabled");
                    },
                    error: function (err, response, errorThrown, jqXHR) {
                        Swal.fire(
                            'Data barang tidak tersedia di katalog!',
                            '',
                            'info'
                        );
                        alert(err.responseText);
                    }
                });
                //window.location = "{{url('/selesai')}}";
            }

        }
    }else{
        if($("#jenis-transaksi").val() == "normal"){
            print(id_trans);
        }else if($("#jenis-transaksi").val() == "suratjalan"){
            printsuratjalan(id_trans);  
           }else{

              printpreorder(id_pre);  
        }
    }



    });

    //input diskon
    $("#prefix").change(function () {
        let val = $("#diskon").val().replace(/[,_]/g, '');
        if($("#diskon").val().replace(/[,_]/g, '').length === 0){
            val = 0;
        }
        subtotalafterdiskon = doDisc(1,$("#subtotal").val().replace(/[,_]/g, ''), val,$("#prefix").val()) ;
        if(hasRetur){
            $("#totality").val(fr(subtotalafterdiskon-$("#ta").val(),""));
        }else{
            $("#totality").val(fr(subtotalafterdiskon,""));
        }
       
    });
    $("#diskon").keyup(function () {
        let val = $(this).val().replace(/[._]/g, '');
        if($(this).val().replace(/[._]/g, '').length === 0){
            val = 0;
        }
        subtotalafterdiskon = doDisc(1,$("#subtotal").val().replace(/[._]/g, ''), val,$("#prefix").val()) ;
        
        if(hasRetur){
            $("#totality").val(fr(subtotalafterdiskon-$("#ta").val(),""));
        }else{
            $("#totality").val(fr(subtotalafterdiskon,""));
        }
    });

    $("#reset-button").click(function () {
        if(!hasfinish){
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "/resettransaction",
            type: "GET",
            success: function () {
                alert("transaksi diulang");
                window.location = "/kasir";
            },
            error: function (err) {
               window.location="/kasir";
            }
        });
        }
    });

   
    function hapusdetail(id_detail) {
        
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                id_detail: id_detail,
                jenis: $("#jenis-transaksi").val()
            },
            dataType : "json",
            type: "GET",
            url: "/removedetail",
            success: function (data) {
                console.log("p : "+ data);
                loader(id_trans,id_pre);
            },
            error: function (err) {
                
                alert(err.responseText);
            }

        });
    }

    //NEXt TRANSACTION
    $("#next-button").click(function (e) {
        window.location = "/selesaitrans";
    });












    $(document).on("click", "tr td .buang", function (e) {
        // $.ajax({
        //     headers: {
        //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //      },
        //      data: {
        //          $id_detail : $(e.target).attr("id_detail")
        //      },
        //      type:"POST",
        //      url: "/removedetail",
        //      success: function(){
        //         alert($(e.target).children("a").attr("id_detail"));
        //      },
        //      error: function(){
        //         alert($(e.target).children("a").attr("id_detail"));
        //      }

        // });
        let id_detail = $(e.target).attr("id_detail") != undefined ? $(e.target).attr("id_detail") : $(e.target).closest(".buang").attr("id_detail");

        Swal.fire({
            title: 'Apakah anda yakin ingin menghapus',
            showDenyButton: true,
            confirmButtonText: 'Batalkan',
            denyButtonText: `Hapus`,
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                Swal.fire('Saved!', '', 'success')
            } else if (result.isDenied) {
               
                hapusdetail(id_detail);
                
            }
        })


    });

    function printpreorder(ids){
      
        $.ajax({
            headers: {
                "X-CSRF-TOKEN" : $("meta[name=csrf-token]").attr('content')
            },
            data: {
                id_pre: ids,
            },
            type: 'post',
            dataType: "json",
            url: "/cetakpreorder",
            success: function(data){
                printJS({printable: data['filename'], type: 'pdf', base64: true});
            },
            error: function(err){
                alert(err.responseText);
            }
        });
    }


    function printsuratjalan(ids){
      
        $.ajax({
            headers: {
                "X-CSRF-TOKEN" : $("meta[name=csrf-token]").attr('content')
            },
            data: {
                id_pre: ids,
            },
            type: 'post',
            url: "/cetaksj2",
            success: function(response){
                printJS({printable: response , type: 'pdf', base64: true});
            },
            error: function(err){
                alert(err.responseText);
                alert(err.responseText);
            }
        });
    }



    
    function print(id) {

        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $("meta[name='csrf-token']").attr('content')
            },
            url: "/cetaknotakecil",
            data: {
                id_trans: id,
            },
            type: "post",
            success: function (response) {
                printJS({ printable: response['filename'], type: 'pdf', base64: true });
            },
            error: function (err) {
                Swal.fire("terjadi kesalahan", "", "info");
                alert(err.responseText);
            }
        });
    };

    $("#suratjalan").click(function () {
        $(".antarcheck:checked").each(function () {
            alert($(this).val());
        })
    });

    $("#list-return").hide();


});