$(document).ready(function(e){
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
            url: "/printnotakecilbc",
            data: {
                id: $(this).attr('id_trans')
            },
            type: "post",
            success: function(response){
            
                printJS({printable: response['filename'], type: 'pdf', base64: true});
            },error: function(err){
                alert(err.responseText);
            }
        });
    });


    $(".printingtt").click(function(){
     
        $.ajax({
            headers: {
                "X-CSRF-TOKEN" : $("meta[name=csrf-token]").attr('content')
            },
            url: "/printtandaterima",
            data: {
                id: $(this).attr('id_trans')
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

     $(".returntrans").click(function(e){
        $("#keterangan-retur").hide();
        $("#nominal-telah-bayar").hide();
         e.preventDefault();
         $("#returnform").modal("show");
     

         $.ajax({
             headers: {
                "X-CSRF-TOKEN" : $("meta[name=csrf-token]").attr('content'),
             },
             data: {
                 'id_trans' : $(this).attr('id_trans')
             },
             url: "/tampilreturn",
             type: "post",
             dataType: "json",
             success: function(data){
          
                let dato = data["datatrans"];
          
                 let no = 1;
                 let row = data['datatrans'].map(function(r,i){
           
                    return `
                        <tr ${r['status'] == 'return' ? 'style="background: lightyellow"' : ""}>
                            <td>${data["hasretur"] > 0 ? '' : '<button nama="'+r['nama_kodetype']+" "+r['nama_merek']+" "+r['nama_produk']+'" jmlretur=0 jml='+ r['jumlah'] +' iddtrans=' + r['id'] + '  class="btn btn-success setjml" type="button">Retur</button>'}</td>
                            <td>${r['nama_kodetype']} ${r['nama_merek']} ${r['nama_produk'] + " "}</</td>
                            <td>${parseInt(r['harga']).toLocaleString()}</</td>
                            <td>${r['potongan']}</</td>
                            <td>${r['jumlah']}</td>
                            <td>${r['status']}</td>
                            <td><p class="returnindi" idindi=${r["id"]}></p></td>
                        </tr>
                    `;
                 });
                 $("#np").text("Nama Pelanggan : "+dato[0]['nama_pelanggan']);
                 $("#tp").text("No. Telp : "+dato[0]['telepon']);
                 $("#almt").text("Alamat : "+dato[0]['alamat']);
                 $("#id_trans").val(dato[0]['kode_trans']);
                 $("#cb").show();
                 $("#id_cb").val(data["no_nota"]);

             

                 if(    data["hasretur"] > 0 || dato[0]["status_trans"] == "return"){
                 
                     $("#re-button").attr('disabled','disabled');
                     $("#buyagain-button").show();
                     $("#cont-disc-retur").hide();
                     $("#cb").hide();
                     $("#cb").removeAttr("id_trans");
                     $("#buyagain-parser").attr("href","/kasir?id_retur="+dato[0]['kode_trans']);
                 }else{
                    
                    $("#cont-disc-retur").show();
                    $("#buyagain-button").hide();
                 
                    $("#re-button").removeAttr('disabled');
                 }


                 //cek apakah nota ini pembelian baru setelah retur
                 if(dato[0]["keterangan_retur"]!=null){
                    $("#nominal-telah-bayar").text("Pembayaran sebelumnya : "+ dato[0]["tlh_bayar"]);
                    $("#keterangan-retur").text("Keterangan Retur : "+dato[0]["keterangan_retur"]);
                    $("#keterangan-retur").show();
                    $("#nominal-telah-bayar").show();
                 }

                 
                 if(data["hascb"] == 1){
                    $("#cb").attr("id_cb",data["id_cb"]);
                 }
              
                 $("#returncont").html(row);

             },error: function(err){
                 alert(err.responseText);
             }
             
         })
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

    
     
     $("#cb").click(function(){
        if($(this).attr("id_cb") != undefined){
            $("#tombolbayarcb").removeAttr("disabled");
            cetakcb($(this).attr("id_cb"));

        }else{
        $(".modalcb").modal("show");
        }
     });


     $("#tombolbayarcb").click(function(){
        $.ajax({
            headers: {
                "X-CSRF-TOKEN" : $("meta[name=csrf-token]").attr('content')
            },
            url: "/cashbacknk",
            data: {
                no_nota: $("#id_cb").val(),
                nominal : $("#nominal-cashback").val().replace(/[._]/g, '')
            },
            type: "post",
            dataType: "json",
            success: function(data){
                $(".modalcb").modal("hide");
                $("#cb").attr("id_cb",data["id"]);
                $("#tombolbayarcb").attr("disabled","disabled");
                cetakcb(data["id"]);
                
            },error: function(err){
                alert(err.responseText);
            }
        });
     });


     function cetakcb(id){
        $.ajax({
            headers: {
                "X-CSRF-TOKEN" : $("meta[name=csrf-token]").attr('content')
            },
            url: "/cetakcashbacknk",
            data: {
                id_trans: id
            },
            type: "post",
            dataType: "json",
            success: function(data){
                printJS({printable: data['file'], type: 'pdf', base64: true});
                
            },error: function(err){
                alert(err.responseText);
            }
        });
     }
  

});


