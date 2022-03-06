$(document).ready(function () {
 


    $("#produk-select").keyup(function(){
        $("#myUL").show();
        let kw = $(this).val();
        $.ajax({
            headers: {
                "X-CSRF-TOKEN" : $("meta[name=csrf-token]").attr('content')
            },
            data: {
                kw: kw
            },
            url: "/searchpro",
            type: "post",
            dataType: "json",
            success: function(data){
                var li = "";
                for(var i = 0;i < data.length;i++){
                    li += `<li>

                               <a kode="${data[i]['kode_produk']}" harga="${data[i]['harga']}" jumlah="1" potongan="0" class="sear">${data[i]["kode_produk"] + " " + data[i]["nama_produk"]}</a>
                            </div>
                        
                        </li>`;
                }
               
                $("#myUL").html(li);
               
            }
            
        })
    });

    $("#myUL").on("click", ".sear",function(event){
        $("#produk-select").val($(event.target).attr("kode"));  
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

        alert($("#status-select").val());

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
            success: function (data) {
                Swal.fire('Berhasil Ditambahkan', '', 'success');
                loaddetail();
                $("#examplemodal").modal('hide');
            },
            error: function (err) {
                alert(err.responseText);
            }
        });
    });


    function loaddetail(kw = null) {
        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $("meta[name='csrf-token']").attr('content')
            },
            url: "/loaddatadetailstok",
            type: "post",
            data:{
                kw: kw
            },
            dataType: "JSON",
            success: function (data) {

                let row = data.map(function (rows, i) {

                    return `
                        <div class="card bg-light">
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
                                        <th style="width:180px;">Nama Produk</th>
                                        <th style="width:70px;">Jumlah</th>
                                        <th style="width:90px;">Status</th>
                                        <th style="width:120px;">Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody class="tbody">
                                <tr>
                                        <td>${rows['created_at']}</td>
                                        <td>${rows['kode_produk']}</td>
                                        <td>${rows['nama_produk']}</td>
                                        <td>${rows['jumlah']} ${rows['satuan']}</td>
                                        <td><div class="status ${rows['status'] != 'masuk' ? "bg-danger": "bg-success"}">${rows['status']}</div></td>
                                        <td>${rows['keterangan']}</td>
                                    </tr
                                </tbody>
                            </table>
                        </div>
                        
                       `;
                });
                $("#dscont").html(row);
            },
            error: function (err) {
            }
        });
    }

    $(document).click(function(){
        $("#myUL").hide();
    });

    $("#myUL").click(function(e){
    e.stopPropagation(); 
    });

    $("#cetaksubmitter").submit(function(e){
        e.preventDefault();
        $.ajax({
            headers: {
                "X-CSRF-TOKEN" : $("meta[name=csrf-token]").attr("content")
            },
            url: "/printstoktrack",
            data: {
                "berdasarkan" : $("#berdasarkan").val()
                
            },
            dataType: "json",
            type:"post",
            success: function(data){
                printJS({printable: data['filename'], type: 'pdf', base64: true});
            },error: function(err){
                alert(err.responseText);
            }
        })
    });
});
