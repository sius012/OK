

$(document).ready(function () {
    $("#toggle-tanggal").hide();
    $("#berdasarkan").change(function () {
        if ($(this).val() == "tanggal") {
            $("#toggle-tanggal").show();
            $("#toggle-hmb").hide();
        } else {
            $("#toggle-tanggal").hide();
            $("#toggle-hmb").show();
        }
    });

    $("#tk").prop('checked',true);
    $("#rs").prop('checked',true);
    $("#tk").click(function(){
        $('.tk').toggle();
    });
    $("#rs").click(function(){
        $('.rs').toggle();
    })






    $(document).click(function () {
        $(".myUL").hide();
    });

    $(document).on("click", ".myUL", function (e) {
        e.stopPropagation();
    });

    $(document).on("click", ".sear", function (event) {
        $(event.target).closest(".parent1").children("input").val($(event.target).attr("kode"));
        $(event.target).closest(".parent1").children("span").text($(event.target).attr("nama"));
        $(event.target).closest(".form-row").children(".parent1").children(".np").val($(event.target).attr('nama'));

    });



    $(".myUL").on("click", ".sear", function (event) {
        $(event.target).closest(".form-row").children(".parent1").children(".inputan-produk").val("hai  ");
        $(".myUL").hide();
       
    });


    loaddetail();
    $("#detailstoksubmitter").submit(function (e) {
        e.preventDefault();
        var data = {
            'created_at': $("#tanggal").val(),
            'kode_produk': $("#produk-select").val(),
            'jumlah': $("#jumlah").val(),
            'status': $("#status-select").val(),
            'keterangan': $("#keterangan").val(),
            
        };

        if($(this).attr('by')=='bypm'){
            data['by'] =$(this).attr('by');
            data['np'] =$(this).attr('np');
            data['hp'] =$(this).attr('hp').replace(/[._]/g,'');
            data['dsc'] =$(this).attr('dsc');
            data['tp'] =$(this).attr('tp');
            data['satuan'] =$(this).attr('stn');
        }





        

        console.log(data);
        $.ajax({
            
            headers: {
                "X-CSRF-TOKEN": $("meta[name='csrf-token'").attr('content')
            },
            data: {
                data: data
            },
            url: "/tambahdetailstok",
            type: "post",
            success: function (response) {
                Swal.fire($("#status-select").val()=="masuk" ? 'Berhasil Ditambahkan' : 'Berhasil dikurangi', '', 'success');
                loaddetail();
              if(response['error'] == undefined){
                window.location = $("#detailstoksubmitter").attr('action');
              }else{
                  Swal.fire('Stok tidak boleh minus');
              }
              
            },
            error: function (err) {
                alert(err.responseText);
            }
        });
    });


    function loaddetail(kw = null) {
        $("#dscont").html(`
        <div class="d-flex justify-content-center">
  <div class="spinner-border" role="status">
    <span class="sr-only">Loading...</span>
  </div>
</div>
        
        `);
        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $("meta[name='csrf-token']").attr('content')
            },
            url: "/loaddatadetailstok",
            type: "post",
            data: {
                kw: kw
            },
            dataType: "JSON",
            success: function (data) {
             

                let row = data["data1"].map(function (rows, i) {

                    return `
                        <div class="card bg-light tk">
                            <div class="card-header">
                                <div class="row">
                                <div class="col-6">
                                <div class=''>Nama Admin: ${rows['name']}</div>
                            </div>
                            <div class="col-6">
                                <div class="  float-right">${rows['created_at']}</div>
                            </div>
                                </div>
                            </div>
                            <table class="table table-borderless">
                                <thead class="thead">
                                    <tr>
                                    <th style="width:180px;">Waktu</th>
                                        <th style="width:170px;">Kode Produk</th>
                                        <th style="width:180px;">Produk</th>
                                        <th style="width:70px;">Jumlah</th>
                                        <th style="width:90px;">Status</th>
                                        <th style="width:120px;">Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody class="tbody">
                                <tr>
                                        <td>${rows['created_at']}</td>
                                        <td>${rows['kode_produk']}</td>
                                        <td>${rows['nama_kodetype']+" "+rows['nama_merek']+" "+rows['nama_produk']}</td>
                                        <td>${rows['jumlah']} ${rows['satuan']}</td>
                                        <td><div class="status ${rows['status'] != 'masuk' ? "bg-danger" : "bg-success"}">${rows['status']}</div></td>
                                        <td>${rows['keterangan']}</td>
                                    </tr
                                </tbody>
                            </table>
                        </div>
                        
                       `;
                });


                let row2 = data["data2"].map(function (rows) {
                    var subrow = "";

                    for (var i = 0; i < rows['jumlahproduk']; i++) {
                        subrow += `
                        <tr>
                        <td>${i + 1}</td>
                        <td>${rows['produk' + i]["kode_produk"]}</td>
                        <td>${rows['produk' + i]["nama_kodetype"]+" "+rows['produk' + i]["nama_merek"]+" " + rows['produk' + i]["nama_produk"]}</td>
                        <td>${rows['jumlah' + i]} ${rows['produk' + i]["satuan"]}</td>
                    </tr`;
                    }
                    return `
                        <div class="card bg-light rs">
                            <div class="card-header">
                                <div class="row">
                                <div class="col">
                                    Retur ke Suplier
                                </div>
                                <div class="col-2">
                                <div class=''>Nama Admin: ${rows['name']}</div>
                                </div>
                            <div class="col-2">
                                <div class="  float-right">${rows['tanggal']}</div>
                            </div>
                                </div>
                            </div>
                            <table class="table table-borderless">
                                <thead class="thead">
                                    <tr>
                                       <th style="width:10px;">No</th>
                                        <th style="width:100px;">Kode Produk</th>
                                        <th style="width:180px;">Produk</th>
                                        <th style="width:70px;">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody class="tbody">
                               ${subrow
                        }
                                </tbody>
                            </table>
                            <div class='card-footer'>Keterangan: ${rows['keterangan']}</div>
                        </div>
                        
                       `;
                });
               console.log(data["data2"]);

                $("#dscont").html(row);
                $("#rscont").html(row2);
            },
            error: function (err) {
                alert(err.responseText);
            }
        });
    }

    $(document).click(function () {
        $("#myUL").hide();
    });

    $("#myUL").click(function (e) {
        e.stopPropagation();
    });

    $("#cetaksubmitter").submit(function (e) {
        e.preventDefault();
        alert( $("#produk-select2").val());
        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $("meta[name=csrf-token]").attr("content")
            },
            url: "/printstoktrack",
            data: {
                berdasarkan: $("#berdasarkan").val(),
                tanggal: $("#tanggal2").val(),
                tanggalakhir: $("#tanggal3").val(),
                produk: $("#produk-select2").val(),
                hmb: $("#hmb").val(),
                keluar: $("#keluars").prop("checked") == true ? "true" : "false",
                masuk: $("#masuks").prop("checked") == true ? "true" : "false",
                suplier: $("#suplier").prop("checked") == true ? "true" :"false",
            gudang: $("#ck").prop("checked") == true ? "true" :"false",

            },
            dataType: "json",
            type: "post",
            success: function (data) {
                printJS({ printable: data['filename'], type: 'pdf', base64: true });
            }, error: function (err) {
                alert(err.responseText);
            }
        })
    });

    $(document).on('keyup', '.inputan-produk', function (e) {
        $(e.target).closest(".parent1").children(".myUL").show();
    

        let kw = $(e.target).val();


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





                $(".drop").show();
                if (data['data'].length > 0) {
                    var li = "";
                    for (var i = 0; i < data['data'].length; i++) {
                        li += `<li>

                                   <a kode="${data['data'][i]['kode_produk']}" nama="${data['data'][i]['nama_kodetype']+" " + data['data'][i]['nama_merek']+" " + data['data'][i]['nama_produk']}" jumlah="1" potongan="0" class="sear">${data['data'][i]['nama_kodetype']+" " + data['data'][i]['nama_merek']+" " + data['data'][i]['nama_produk']}</a>
                                </div>
                            
                            </li>`;
                    }
                 

                    $(e.target).closest(".parent1").children(".myUL").html(li);
                } else {
                    $(".drop").hide();
                }
               
                console.log(data.length);

            },
            error: function (err, response,) {
                Swal.fire("terjadi kesalahan", "", "info");
            }
        });


    });

    $("#tambahbutton").click(function () {
        $("#first-row").after(`<div class="form-row mt-3">
        <div class="col-4    parent1">
       
            <input type="text" class="form-control inputan-produk" placeholder="Ketik Kode Atau Nama" name='kode[]'>
            <ul class="myUL">

            </ul>
        </div>
        <div class="col-5 parent1">
    
        <input type="text" class="form-control np" placeholder="Nama Produk" name=''>
        
           </div>
        <div class="col-2 parent1">
    
            <input type="text" class="form-control" placeholder="Jumlah" required name='jumlah[]'>
            
        </div>
        <div class="col-1 parent1">
    
        <a class='btn btn-danger rm'><i class='fa fa-trash'></i></a>
        
    </div>

    </div>`);
    });

    $(document).on('click',".rm",function(e){
        $(e.target).closest(".form-row").remove();
    });

    $(document).on("click", ".btndel", function (e) {
        $(e.target).closest(".form-row").remove();
    });


    $("#the-filter").submit(function(e){
        e.preventDefault();
        let theform = {
            md:$("#m-f").val(),
            sd:$("#s-f").val(),
            tipe:$("#tipe-f").val(),
            produk:$("#produk-f").val(),
        }

        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $("meta[name='csrf-token'").attr('content')
            },
            data: theform,
            url: "/filterdetailstok",
            type: "post",
            dataType: "json",
            success: function (data) {
               
                loadfilter(data);
                $("#filtermodal").removeClass("in");
  $(".modal-backdrop").remove();
  $('body').removeClass('modal-open');
  $('body').css('padding-right', '');
  $("#filtermodal").hide();
              
                
            },
            error: function (err) {
                alert(err.responseText);
            }
        });
        $("#filtermodal").modal('hide');
        
        console.log(theform);
    });
});


function loadfilter(data) {

    let row = data["data1"].map(function (rows, i) {

        return `
            <div class="card bg-light tk">
                <div class="card-header">
                    <div class="row">
                    <div class="col-6">
                    <div class=''>Nama Admin: ${rows['name']}</div>
                </div>
                <div class="col-6">
                    <div class="  float-right">${rows['created_at']}</div>
                </div>
                    </div>
                </div>
                <table class="table table-borderless">
                    <thead class="thead">
                        <tr>
                        <th style="width:180px;">Waktu</th>
                            <th style="width:170px;">Kode Produk</th>
                            <th style="width:180px;">Produk</th>
                            <th style="width:70px;">Jumlah</th>
                            <th style="width:90px;">Status</th>
                            <th style="width:120px;">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="tbody">
                    <tr>
                            <td>${rows['created_at']}</td>
                            <td>${rows['kode_produk']}</td>
                            <td>${rows['nama_kodetype']+" "+rows['nama_merek']+" "+rows['nama_produk']}</td>
                            <td>${rows['jumlah']} ${rows['satuan']}</td>
                            <td><div class="status ${rows['status'] != 'masuk' ? "bg-danger" : "bg-success"}">${rows['status']}</div></td>
                            <td>${rows['keterangan']}</td>
                        </tr
                    </tbody>
                </table>
            </div>
            
           `;
    });


    let row2 = data["data2"].map(function (rows) {
        var subrow = "";

        for (var i = 0; i < rows['jumlahproduk']; i++) {
            subrow += `
            <tr>
            <td>${i + 1}</td>
            <td>${rows['produk' + i]["kode_produk"]}</td>
            <td>${rows['produk' + i]["nama_kodetype"]+" "+rows['produk' + i]["nama_merek"]+" " + rows['produk' + i]["nama_produk"]}</td>
            <td>${rows['jumlah' + i]} ${rows['produk' + i]["satuan"]}</td>
        </tr`;
        }
        return `
            <div class="card bg-light rs">
                <div class="card-header">
                    <div class="row">
                    <div class="col">
                        Retur ke Suplier
                    </div>
                    <div class="col-2">
                    <div class=''>Nama Admin: ${rows['name']}</div>
                    </div>
                <div class="col-2">
                    <div class="  float-right">${rows['tanggal']}</div>
                </div>
                    </div>
                </div>
                <table class="table table-borderless">
                    <thead class="thead">
                        <tr>
                           <th style="width:10px;">No</th>
                            <th style="width:100px;">Kode Produk</th>
                            <th style="width:180px;">Produk</th>
                            <th style="width:70px;">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody class="tbody">
                   ${subrow
            }
                    </tbody>
                </table>
                <div class='card-footer'>Keterangan: ${rows['keterangan']}</div>
            </div>
            
           `;
    });
   // alert(row);

    $("#dscont").html(row);
    $("#rscont").html(row2);
}
