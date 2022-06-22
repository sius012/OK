

$(document).ready(function(e){
    $(".hapustrans").click(function(e){
        e.preventDefault();

        Swal.fire({
            title: 'Apakah anda yakin ingin menghapus?',
            showDenyButton: true,
            confirmButtonText: 'Ya',
            denyButtonText: `Batal`,
          }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                $.ajax({
                    headers: {
                        "X-CSRF-TOKEN" : $("meta[name=csrf-token]").attr('content')
                    },
                    data: {
                        id_trans: $(this).attr("id_trans")
                    },
                    type: "post",
                    url: "/hapussuratjalan2",
                    success: function(){
                        window.location = "/menusuratjalan";
                    },error: function(){
                        alert("error");
                    }
                });
            } else if (result.isDenied) {
               
            }
          });
        
    });
    
    $("#infomodal").modal("show");

    $(document).on('click', '.infopreorder', function(e){
    });


    $("#usercetak").submit(function(e){

    });

    $(".hps-draf").click(function(){
        $.ajax({
            headers: {
                "X-CSRF-TOKEN" : $("meta[name=csrf-token]").attr('content')
            },
            url: "/hapusdraf",

            type: "post",
            success: function(response){
                window.location = "/transaksi";
            },error: function(err){
                alert(err.responseText);
            }
        });
    });

    $(".printing").click(function(){
        $.ajax({
            headers: {
                "X-CSRF-TOKEN" : $("meta[name=csrf-token]").attr('content')
            },
            url: "/cetaksj2",
            data: {
                id_pre: $(this).attr('id_trans')
            },
            type: "post",
            success: function(response){
                printJS({printable: response, type: 'pdf', base64: true});
            },error: function(err){
                alert(err.responseText);
            }
        });
    });

    $(".content-wrapper").on("click", ".datatrans", function(event){
        $("#exampleModal").modal('show');
       
        $.ajax({
            headers:  {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                 }, 
            data: {id: $(event.target).is(".datatrans") ? $(event.target).attr('id_trans') : $(event.target).closest('.datatrans').attr('id_trans')},
            url: "/loadsingletrans",
            dataType: "JSON", 
            type: "post",
            success: function(data){   
              
                $(".t2after").hide();
                $(".t3after").hide();
              
             
              
                $(".viatermin1").text("Via : "+data['cicilan'][0][0]['via']);
                $(".kasirtermin1").text("Kasir : " + data['cicilan'][0][1]);
             
                var dato = data['trans'].map(function(transo){
                    return "<tr>"+"<td>"+transo["kode_produk"]+"</td>"+"<td>"+transo["nama_produk"]+"</td>"+"<td>"+transo["merk"]+"<td>"+transo["jumlah"]+"</td>"+"<td>"+transo["kategori"]+"</td>"+"</tr>";   
                });
                     
             
           
               $("#namapelanggan").html("Nama Pelanggan : " );
               $(".tgltermin1").text("Tanggal Pembayaran :  "+ data['cicilan'][0][0]['created_at']);
               $(".nominaltermin1").html("Nominal :  "+ data['cicilan'][0][0]['nominal']);



               console.log(data['cicilan']);


               $("#dtcontent").html(dato);
               $("#tanggaltrans").html(data['detail'][0]['created_at']);
               $("#idcontainertrans").val(data['trans'][0]['kode_trans']);


              
         if(data['cicilan'][1][0]['nominal'] == null){
            $(".termin3a").addClass("disabled");
          
         }else{
            $("#termin2form").hide();
            $(".t2after").show();
            $(".kasirtermin2").text("Kasir : " + data['cicilan'][1][1]);
            $(".tgltermin2").text("Tanggal Pembayaran :  "+ data['cicilan'][1][0]['created_at']);
            $(".nominaltermin2").html("Nominal :  "+ data['cicilan'][1][0]['nominal']);
            $(".viatermin2").text("Via : "+data['cicilan'][1][0]['via']);
         }

         if(data['cicilan'][2][0]['nominal'] != null){
 
            $("#termin3form").hide();
            $(".t3after").show();
            $(".kasirtermin3").text("Kasir : " + data['cicilan'][2][1]);
            $(".tgltermin3").text("Tanggal Pelunasan :  "+ data['cicilan'][2][0]['updated_at']);
            $(".nominaltermin3").html("Nominal :  "+ data['cicilan'][2][0]['nominal']);
            $(".viatermin3").text("Via : "+data['cicilan'][2][0]['via']);
         }

       
                
                
            },
            error: function(err){
            }
        });
    });


    function bayarcicilan(termin, data){
     
        $.ajax({
            headers: {
                'X-CSRF-TOKEN' : $("meta[name='csrf-token']").attr('content')
            },
            data: {
                kode_transaksi: data['kode_transaksi'],
                termin: termin,
                nominal: data['nominal'],
                via:data['via']
            },
            url: "/bayarcicilan",
            type: "post",
            success: function(){
                swal.fire({
                    title: "Termin " + termin+" telah dilunasi",
                    type: "success",
                }).then(function(e){
                    window.location = "/transaksi"
                })
            },
            error: function(err){
            }
        });
    }

    $("#termin2form").submit(function(e){
        e.preventDefault();
        let dats = {
            "kode_transaksi" : $("#idcontainertrans").val(),
            "via" : $('.viatermin2').val(),
            "nominal" : $('.nominaltermin2').val(),
        };
       bayarcicilan(2, dats);
    });

    $("#termin3form").submit(function(e){
        e.preventDefault();
        let dats = {
            "kode_transaksi" : $("#idcontainertrans").val(),
            "via" : $('.viatermin3').val(),
            "nominal" : $('.nominaltermin3').val(),
        };
        Swal.fire({
            title: 'Apakah anda yakin ingin melunasi',
            showDenyButton: true,
            confirmButtonText: 'Batalkan',
            denyButtonText: `Lunasi`,
          }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
            } else if (result.isDenied) {
               bayarcicilan(3, dats);
            }
          });
        
      
     });

    

     $(".btn-bayar").click(function(e){
        e.preventDefault();
        $("#tombolbayar").attr('id_trans',$(this).attr('id_trans'));
        $("#totalbayar").val($(this).attr('subtotal'));
        $("#td").val($(this).attr('td'));
     });

     $("#tombolbayar").click(function(){
        let total=parseInt($("#totalbayar").val().replace(/[._]/g, ''));
        let td=parseInt($("#td").val().replace(/[._]/g, ''));
        
        
        let id = $(this).attr('id_trans');
        let nominal = parseInt($("#nominal-bayar").val().replace(/[._]/g, ''));
      
        if(nominal+td>=total){
            $.ajax({
                headers: {
                    "X-CSRF-TOKEN" : $("meta[name=csrf-token]").attr('content')
                },
                url: "/bayarcicilannotakecil",
                data: {
                    'id': id,
                    'nominal': nominal,
                },
                type:'post',
                success: function(){
                    window.location = "/transaksi";
                },
                error: function(err){
                    alert(err.responseText);
                }
            });
        }else{
            Swal.fire("Nominal kurang","","info");
        }
       
     });

     
      
  

});


