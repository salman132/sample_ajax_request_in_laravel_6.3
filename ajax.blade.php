<?php

public function show(Request $request, $id){
        $employees = Employee::where('department',$id)->get();
        if($request->ajax()){
            return view('admin.roaster.projects.employees',compact('employees'))->render();
        }

    }


?>


<script>

     $('#dep_id').on('change',function () {
                var dep_id = $(this).val()

                $.ajax({
                    type:'GET',
                    url: '{{url("eroster/employee/department")}}/'+ dep_id,

                    success:function (response) {
                        console.log(response);
                        $('#emp_id').html('');

                        $('#emp_id').append(response);

                    },
                    error:function (xhr) {
                        console.log(xhr)
                    }

                })

            });



</script>
