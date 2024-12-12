@include("emails.partials.header")
<h3> Hello,</h3>
<div>Welcome to Totably, your "Restaurant Manager" account has just been created. Please find below your credentials and the link to access totably site:</div>
<br>
<br>
<div>Email : {{$username}}</div>
<div>Password : {{$code}}</div>
<div>Link : {{$app_url}}</div>
<br>
<br>
<div>Regards,</div>
<div>Totably team</div>
@include("emails.partials.footer")
