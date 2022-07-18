<!DOCTYPE html>
<html>
<head>
<style> 
*{
    margin: 0px;
    font-size: 8pt;
}
#main {
    width: 15cm;
    margin-left: 3px;
    
 
}
body{
    margin-top: 20px;
}
#main div {
  width: 5cm;
  height: 2cm;
  display: inline-block;
  
}
.cardi{
    border: 1px solid black;
    display: block;
    padding: 0px;
}
.barcode{
    letter-spacing: 7.2pt;
    font-size: 6pt;
    font-weight: bold;
}
.jdl{
 
    top: 10px;
    font-size: 8pt;
    font-weight: bold;
    line-height: 2px;
  
}
img{
    width: 30px;
   
    transform: translate(166px,-50px) rotate(-90);
    position: absolute;
    z-index: 20;
}

.barcoder{
    transform: translate(0px 50px) !important;
    padding: 10px;
}

.cont-main{
   position: absolute;
   padding-top: 10px;
   transform: translate(-5px,0px)
}


</style>
</head>
<body>


<div id="main" >
    @foreach($data as $datas)
    @php 
    $flenght = strlen($datas->nama_kodetype." ".$datas->nama_merek." ".$datas->nama_produk);
    $fontsize = 220 / $flenght;

    if($fontsize<=10){
        $fontsize+=1;
    }
    if($fontsize>=20){
        $fontsize=6;
    }
    @endphp
    <div style="margin-left: 2px; margin-top: 12px;" class="cardi">
    <div class="cont-main">
        <div style="margin-left: 12px">
    <span class="jdl"  style="text-align:left; width:10px; font-size:{{$fontsize}}">{{$datas->nama_kodetype}} {{$datas->nama_merek}} {{$datas->nama_produk}}</span>
    <div style="padding-top: 4px">
        <span class="barcoder" style="">{!! DNS1D::getBarcodeHTML($datas->kode_produk, 'C128',1.35,30) !!}</span><br>
        <span class="barcode" >{{$datas->kode_produk}}</span>
        </div>
        
        </div>
        <img  src="{{public_path('assets/ok.png')}}" alt="">
    </div>
  
    </div>
    @endforeach
  

</div>



</body>
</html>