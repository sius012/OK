$(document).ready(function(){
    
    var jmlproduk = 0;
    //mengklik tombol return disetiap item
    $(document).on("click", ".setjml", function(event){
        let iddtrans = $(event.target).attr("iddtrans");
        let jmlretur = $(event.target).attr("jmlretur");
        let jml = $(event.target).attr("jml");
        let nama = $(event.target).attr("nama");

        jmlproduk = jml;
        
        //menset modal sesuai data
        $(".setjmlreturn .title").text("tentukan jumlah retur dari "+nama);
        $(".setjmlreturn .inputan").val(jmlretur);
        $(".setjmlreturn .idnya").val(iddtrans);

        $(".setjmlreturn").modal("show");
    });

    //ketika tombol oke dimodalnya diklik
    $(".okebutton").click(function(){
        let value = $(this).parent().parent().children("div").children(".inputan").val();
        let idnya = $(this).parent().parent().children("div").children(".idnya").val();
        $("[iddtrans="+idnya+"]").attr("jmlretur", value);
        let valueawal = $("[iddtrans="+idnya+"]").attr("jml");

        if(valueawal < 0 || valueawal > jmlproduk){
            if(valueawal > jmlproduk){
                alert("pastikan jumlahnya tidak melebihi jumlah barang yg dibeli");
            }
           $("[iddtrans="+idnya+"]").removeClass("readytoreturn");
        }else{
           
            if(value < 1){
                $("[iddtrans="+idnya+"]").removeClass("readytoreturn");
                $("[idindi="+idnya+"]").text("");
            }else{
                $("[iddtrans="+idnya+"]").addClass("readytoreturn");
                $("[idindi="+idnya+"]").text(value);
            }
            
        }
      
       
        $(".setjmlreturn").modal("hide");
    });


    //ketika tombol return ditekan
    $("#returnform").submit(function(e){
        $("#returnform button").attr("disabled","disabled");
        e.preventDefault();
        
        var jumlahygdiretur = 0;

        var arridtrans = [];
        var arrjmlreturn = [];
    //mengambil jumlah return dari setiap produknya disetiap transaksi
        $(".readytoreturn").each(function(index,element){
            let idnya = $(element).attr("iddtrans");
            let jumlahreturn = $(element).attr("jmlretur");
            jumlahygdiretur +=1;
            arridtrans.push(idnya);
            arrjmlreturn.push(jumlahreturn);
        });

        console.log(arrjmlreturn);

        
        //lakukan ajax ke controller
        $.ajax({
            headers: {
                "X-CSRF-TOKEN" : $("meta[name=csrf-token]").attr('content')
            },
            url: "/doreturn",
            type: "post",
            data: {
                iddtrans : arridtrans,
                jmlreturn : arrjmlreturn
            },
            success: function(response){
                
                window.location = "/transaksi";
            },error: function(err){
                console.log(err.responseText);
                alert(err.responseText);
            }

        });
        


    });

    //ketika inputan jumlah diketikan
    $(".inputan").keyup(function(){
        if(parseInt($(this).val()) < 0 || parseInt($(this).val()) > jmlproduk){
            $(this).val("");
        }
    });

    
});