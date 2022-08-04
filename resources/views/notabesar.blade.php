@php $whoactive='notabesar';
$master='kasir' @endphp
@extends('layouts.layout2')

@section('pagetitle', 'Nota Besar')
@section('icon', 'fa fa-sticky-note mr-2 ml-2')
@section('title', 'Nota Besar')

@section('js')
<script src="{{ asset('js/print.js') }}"></script>
<script>
    function searchnota(kw){
        $.ajax({
            headers: {
                "X-CSRF-TOKEN" : $("meta[name=csrf-token]").attr('content')
            },
            data: {
                kw : kw
            },
            url: "/searchnotapreorder",
            type: "post",
            dataType: "json",
            success: function(data){
                console.log(data);
                let row = data.map(function(datas){
                    return `
                <li><a href="#"  id_nb = ${datas['id_transaksi']} class='${datas['jatuh_tempo'] == null && datas['status']  == "menunggu"  && datas['kunci'] == null ? "disab" :  "cc"}'>${datas['no_nota'] + "  " + "Termin: " + datas['termin']}</a></li>

                    `;
                });

                $("#myUL").html(row);
            },
            error: function(err){
                Swal.fire(err.responseText)
            }

        });
    }
    function tampilkannb($id_trans){
        $(".readonly").attr("readonly","readonly");
        $("#notabesar").hide();
        $(".kunci").hide();
        $("#suratjalan").hide();
        $("#buttonreset").attr('href','/transaksipreorder');
        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $("meta[name=csrf-token]").attr('content')
            , }
            , data: {
                id_transaksi: $id_trans
            }
            , url: "/getnb"
            , type: "post"
            , dataType: "json"
            , success: function(data) {
                $("#baseinputnb input, label").show();
         

                if(data['nb'][0]['termin'] == 2){
                    $(".kunci").show();
                    $("#kunci").val(data['nb'][0]['kunci']);
                    $(".jt").show();
                }else{
                    $(".kunci").hide();
                 
                }
                console.log(data);
                $("#tt").text(data["nb"][0]["termin"] == 3 ? "PELUNASAN" : "Termin: " + data["nb"][0]["termin"]);
                $("#baseinputnb .col").show();
               
                var dateObj = new Date();
                var month = dateObj.getUTCMonth() + 1; //months from 1-12
                var day = dateObj.getUTCDate();
                var year = dateObj.getUTCFullYear();

                newdate = year + "-" + month + "-" + day;
                $("#ttd").val(data['nb'][0]['ttd']);
                $("#telepon").val(data['nb'][0]['telepon']);
                $("#up").val(data['nb'][0]['up']);
                $("#us").val(data['nb'][0]['us']);
                $("#brp").val(data['nb'][0]['brp']);
                $("#gm").val(data['nb'][0]['gm']);
                $("#total").val(parseInt(data['nb'][0]['total']).toLocaleString());
                $("#total2").val(parseInt(data['nb'][0]['total']));
                $("#nn").html("No Nota: " + "<a class='nnt' href=#>"+data["nb"][0]["no_nota"]+"</a>");
                $("#tgl").val('{{date("Y-m-d")}}');
                $("#jt").val(data["nb"][0]["jatuh_tempo"]);
                $("#termin").val(data['nb'][0]['termin']);
                 $("#jt").attr("disabled",'disabled');

                let row = data["opsi"].map(function(e, i) {
                    return `
                    <div class="form-group">
                        <label>${e['judul']}</label>
                        <input type="text" readonly class="form-control isi${i+1} readonly" id="exampleInputPassword1" value="${e['ket']}">
                    </div>
                    `;

                });


                $(".opsigrup").html(row);

                $("#buttonsubmit").text("Bayar");
                $("#preorderform").attr("action", "/bayarpreorder");
                $("#id_trans").val(data["nb"][0]["id_transaksi"]);
                $(".td").show();
                $("#td2").val(parseInt(data["td"]));
                $(".td").children("#td").val(parseInt(data["td"]).toLocaleString());
                $("#addopsi").hide();
               // $("#suratjalan").attr("disabled","disabled");
                if (data["nb"][0]["status"] == "dibayar") {
                    $("#us").attr("disabled", "disabled");
                    $("#us").val(parseInt($("#us").val()).toLocaleString());
                    $("#brp").attr("disabled", "disabled");
                    $("#buttonsubmit").attr("disabled", "disabled");
                    $("#buttonsubmit").text("Sudah Lunas");
                    $("#buttonsubmit").removeClass("btn-primary");
                    $("#buttonsubmit").addClass("btn-success");
                    $("#printbutton").removeAttr("disabled");
                    $("#suratjalan").removeAttr("disabled");
                   
                    if(data["nb"][0]["termin"] == 2){
                        $("#suratjalan").show();
                    }
                  
 

                } else {
                    $("#us").removeAttr("disabled");
                    $("#us").removeAttr("disabled");
                    $("#buttonsubmit").removeAttr("disabled");
                    $("#buttonsubmit").removeClass("btn-success");
                    $("#buttonsubmit").addClass("btn-primary");
                    $("#buttonsubmit").text("Bayar");
                    $("#printbutton").attr("disabled", "disabled");
                    $("#kunci").removeAttr("readonly");
                    $("#jt").removeAttr('readonly');
                    
                   $("#jt").removeAttr('readonly');
                }

                if(data['nb'][0]['kunci'] == null){
                    $("#tbsj").text("Kirim");
                    $("#jt").removeAttr("disabled");
                }else{
                    
                    $("#tbsj").text("Cetak");
                }
                $(".readonly").attr('readonly', 'readonly');
            }
            , error: function(err) {
              alert("ei");
             //   Swal.fire("lol", "", "info");
             alert(err.responseText);
            }
        });



    }
</script>
<script src="{{ asset('js/transaksi.js') }}"></script>
<script src="{{ asset('js/notabesar.js') }}"></script>
@isset($id)
<script>
    $(document).ready(function() {
        $("#resetbutton").attr('href','/transaksipreorder');
        $("#notabesar").css("pointer-event",'none');
        var idt = "{{$id}}";
        tampilkannb(idt);

    });

</script>
@endisset

@stop

@section('css')
<link rel="stylesheet" href="{{asset('css/notabesar.css')}}">
@stop

@section('content')
<div class="container-fluid ">
    <div class="col">
        <div class="form-group">
            <input type="text" id="searcher-nota" class="form-control" placeholder="Cari Nomer Nota">
            <ul id="myUL">
            </ul>
        </div>
    </div>

    <div class="col">

        <input type="hidden" id="id_trans" val="0">
        
        <input type="hidden" id="termin" val="0">
        <form id="preorderform" action="/tambahpreorder">
        <div class="card">

            <div class="card-header">
                
                    <h4 id="tt" class="m">Tanda Terima</h4><span id="nn" style="color:#747474;" class="mr-2"> No Nota : ?</span><i style="color:#747474;" class="fa fa-copy mr-3"></i>
            </div>
            <div class="card-body">
                <label for="notabesar">Pilih Nota Besar</label>
                <select name="" class="custom-select mb-3" id="notabesar">
                    <option value="pintugarasi">Pintu Garasi</option>
                    <option value="pintugadandp">Pintu GA & DP </option>
                    <option value="autog">Auto Gate & Auto Garage</option>
                    <option value="upvc">UPVC</option>
                    <option value="omge">OMGE</option>
                    <option value="jasapasang">Jasa Pasang</option>
                </select>
                <div class="form-group">
                    <label for="exampleInputEmail1">Tanggal </label>
                    <input type="date" class="form-control form-control-" required id="tgl" value="{{date('Y-m-d')}}">
                </div>
                <br>
                <div class="row" id="baseinputnb">
                    <div class="col">

                        <div class="form-group">
                            <label for="exampleInputEmail1">Telah diterima dari</label>
                            <input type="text" class="form-control readonly" id="ttd" aria-describedby="emailHelp" required>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">No. Telepon</label>
                            <input type="text" class="form-control readonly" id="telepon" aria-describedby="emailHelp" required>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Untuk Proyek</label>
                            <input placeholder="" type="text" class="form-control readonly" id="up" required>
                        </div>
                        <div class="form-group td">
                            <label for="exampleInputPassword1">Telah Dibayar</label>
                            <input type="text" class="form-control readonly" id="td">
                            <input type="hidden" class="form-control readonly" id="td2">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Uang sejumlah</label>
                            <input type="text" class="form-control uang" required id="us" required>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Berupa</label>
                            <input type="text" class="form-control " required id="brp" required>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Guna Membayar</label>
                            <input type="text" class="form-control readonly" id="gm" required>
                        </div>
    


                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="exampleInputPassword1">Total</label>
                            <input type="hidden" class="form-control readonly" id="total2">
                            <input type="text" class="form-control readonly uang" id="total" required>
                        </div>
                        <div class="form-group opsigrup">

                            <input placeholder="Judul" type="text" class="form-control form-control-sm mb-3 title1 readonly" id="exampleInputPassword1">
                            <input placeholder="Keterangan" type="text" class="form-control isi1 readonly" id="exampleInputPassword1">
                        </div>
                    </div>
                    <div class="col">


                    </div>
                </div>
            </div>
            <div class="row ml-3 mb-3">
                <button type="submit" class="btn btn-primary" id="buttonsubmit">Kirim</button>
                <a type="button" class="btn btn-primary ml-2" href="/notabesar" id="resetbutton"><i class="fa fa-back"></i>Kembali</a>
               
                <button type="button" class="btn btn-warning ml-2" id="printbutton"><i class="fa fa-print mr-2"></i>Print</button>
                <button type="button" id="suratjalan" data-toggle="modal" data-target="#exampleModal" class="btn btn-primary float-right ml-2">Surat Jalan</button>
            </div>
        </div>
        </form>
    </div>


</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Surat Jalan</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="" id="sjsubmit">
        <div class="form-group">
          <label>Jatuh Tempo : </label>
          <input id="jt" type="date" class="form-control">
        </div>
        <div class="form-group">
          <label>Kunci : </label>
          <input id="kunci" type="text" class="form-control">
        </div>
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
        <button type="submit" class="btn btn-primary" id="tbsj">Simpan</button>
      </div>
</form>
    </div>
  </div>
</div>



<script>
    $(document).ready(function() {




        $(".uang").keyup(function() {
            $(this).val(formatRupiah($(this).val(), ""))
        });


        function formatRupiah(angka, prefix) {
            var number_string = angka.replace(/[^,\d]/g, '').toString()
                , split = number_string.split(',')
                , sisa = split[0].length % 3
                , rupiah = split[0].substr(0, sisa)
                , ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            // tambahkan titik jika yang di input sudah menjadi angka ribuan
            if (ribuan) {
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return prefix == undefined ? rupiah : (rupiah ? '' + rupiah : '');
        }
    });

</script>
@stop