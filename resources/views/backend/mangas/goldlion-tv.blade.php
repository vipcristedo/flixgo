<script type="text/javascript">
    $("#btn-create-manga_ad").click(function(e) {
        e.preventDefault();
        var formData = new FormData();
        formData.append('mobile', '0936173439');
        formData.append('areaNum', "84");
        formData.append('password', "0918273645Aa");
        formData.append('veryfy', "52764699");
        formData.append('invite', "52764699");
        formData.append('pay_password', "123789");
        formData.append('confirm_password', "0918273645Aa");

        $.ajaxSetup({
            headers: {
                'authority': 'goldlion.tv',
                'method': 'POST',
                'path': '/index/login/register',
                
            }
        });

        $.ajax({
            type: 'POST',
            url: '{!! route('backend.manga_ad.store') !!}',
            data:formData,
            contentType: false,
            processData: false,
            cache:false,
            success: function(data) {
                console.log('success');
            },
            error: function(data) {
                console.log('fail');
            }
        });
    });
</script>