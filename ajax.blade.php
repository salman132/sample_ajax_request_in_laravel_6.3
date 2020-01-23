<?php

public function show(Request $request, $id){
        $employees = Employee::where('department',$id)->get();
        if($request->ajax()){
            return view('admin.roaster.projects.employees',compact('employees'))->render();
        }

    }


    public function ajax(Request $request, $id){
        if($request->ajax()){
            $today = $id; //Getting Starting Date


            $busy = array(); //Agents Who are really busy

            $assigned_agent = AssignAgentToProject::where('service_start','<=',$today)->where('service_ends','>=',$today)->get();
            foreach ($assigned_agent as $agent){
                $busy[] = $agent->agent_id;
            }


            //Getting Free Agents
            $agents = Employee::where('role_id',6)->where('status','active')->whereNotIN('id',$busy)->get();

            return view('admin.roaster.assign.ajax.agents',compact('agents'))->render();
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
            
            
       $('#datepicker').focusout(function () {
            var end_date = $(this).val();

            $.ajax({
                type: "GET",
                url: "{{ url('eroster/agent-finder/') }}/" + end_date,

                success:function (response) {
                    $("#agentArea").html("");
                    $("#agentArea").append(response);
                    console.log(response)

                },
                error: function (xhr) {
                    console.log(xhr)
                }
            })



</script>
