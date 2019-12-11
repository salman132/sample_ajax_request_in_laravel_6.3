    <script>
        $(document).ready(function () {

            $.ajaxSetup({

                headers: {

                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

                }

            });

            $("#city").change(function () {
                var val = this.value;

                $.ajax({
                    type: 'GET',
                    url: '/home',
                    data:{
                        val: val,
                    },
                    success:function(data){
                        $(".remove").remove();
                        $(".success").append('<div class="text-success remove">Your data is: '+ val + '</div>')
                    }
                })
            });
        });
    </script>
