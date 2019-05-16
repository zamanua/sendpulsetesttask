<div class="bd-example" data-example-id="">
	<form class="form-signin" action="/user_registration" method="post">
	  <input type="hidden" name="is_post" value="1">
	
	  <h1 class="h3 mb-3 font-weight-normal">Registration</h1>
	
	  <div class="form-group row">
	  	<label for="inputName" class="col-2 col-form-label">Name</label>
  	  	<div class="col-10">
		  <input name="data[name]" type="text" id="inputName" class="form-control" placeholder="name" required autofocus>
	    </div>
      </div>
	
	  <div class="form-group row">
	    <label for="inputLogin" class="col-2 col-form-label">Login</label>
	  	<div class="col-10">
        <div class="input-group mb-3">
          <div class="input-group-prepend">
            <span class="input-group-text" id="basic-addon1">@</span>
          </div>
	        <input name="data[login]" type="email" id="inputLogin" class="form-control" placeholder="Login" required autofocus aria-describedby="basic-addon1">
        </div>
      </div>
    </div>

	
	  <div class="form-group row">
	    <label for="inputPassword" class="col-2 col-form-label">Password</label>
  	  	<div class="col-10">
		  <input name="data[password]" type="password" id="inputPassword" class="form-control" placeholder="Password" required autofocus>
	    </div>
      </div>
	
	  
	  <button class="btn btn-lg btn-primary btn-block" type="submit">Register</button>
	</form>
</div>
