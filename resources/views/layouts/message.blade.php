@if(Session::has('success'))
<div class="alert alert-success">
   {{Session::get('success')}} 
</div>    
@endsession
@if(Session::has('error'))
<div class="alert alert-danger">
{{Session::get('error')}} 
</div>    
@endsession