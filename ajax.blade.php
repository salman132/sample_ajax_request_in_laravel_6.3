<?php

public function show(Request $request, $id){
        $employees = Employee::where('department',$id)->get();
        if($request->ajax()){
            return view('admin.roaster.projects.employees',compact('employees'))->render();
        }

    }

   public function ajax(Request $request, $id){
    if($request->ajax()){
        $startTime = $id; //Getting Starting Date
        $endTime = $request->end_date;


        $busy = array(); //Agents Who are really busy


        $assigned_agent = AssignAgentToProject::where(function ($dateQuery) use ($startTime, $endTime){

            $dateQuery->where(function ($query) use ($startTime, $endTime) {$query->where(function ($q) use ($startTime, $endTime){
                $q->where('service_start','>=',$startTime)->where('service_ends','<=',$endTime); })
                ->orWhere(function ($q) use ($startTime, $endTime){ $q->where('service_start','<',$startTime)->where('service_ends','>',$startTime); });})

                ->orwhere(function ($query) use ($startTime, $endTime) {$query->where(function ($q) use ($startTime, $endTime){ $q->where('service_start','>',$startTime)->where('service_ends','<',$endTime); })
                    ->orWhere(function ($q) use ($startTime, $endTime){ $q->where('service_start','<',$endTime)->where('service_ends','>',$endTime); });});
        })->get();




        foreach ($assigned_agent as $key => $agent){
            $busy[] = explode(",",$agent->agent_id);
        }

        if(count($assigned_agent) >0){

            $agents = Employee::where('role_id',6)->where('status','active')->whereNotIN('id',$busy[0])->get();
        }
        else{

            //Getting Free Agents
            $agents = Employee::where('role_id',6)->where('status','active')->whereNotIN('id',$busy)->get();

        }




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
