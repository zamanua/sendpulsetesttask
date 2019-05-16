<div class="bd-example" data-example-id="">
	<form class="form-signin" action="/list_add" method="post">
	  <input type="hidden" name="is_post" value="1">
	  {if $aExist.id}<input type="hidden" name="id" value="{$aExist.id}">{/if}
	  {if $iIdParent}<input type="hidden" name="data[id_parent]" value="{$iIdParent}">{/if}
	
	  <h1 class="h3 mb-3 font-weight-normal">Create new TODO</h1>
	
	  <div class="form-group row">
	  	<label for="inputName" class="col-2 col-form-label">Name</label>
  	  	<div class="col-10">
		  <input name="data[name]" value="{$aExist.name}" type="text" id="inputName" class="form-control" placeholder="name" required autofocus>
	    </div>
      </div>
	
	  <div class="form-group row">
	    <label for="inputDescription" class="col-2 col-form-label">Description</label>
  	  	<div class="col-10">
		  <input name="data[description]" value="{$aExist.description}" type="text" id="inputDescription" class="form-control" placeholder="Description" required autofocus>
	    </div>
      </div>
	
	  <div class="form-group row">
	    <label for="inputDate" class="col-2 col-form-label">Date</label>
  	  	<div class="col-10">
		  <input name="data[post_date]" value="{$aExist.post_date}" type="datetime-local" id="inputDate" class="form-control" placeholder="Date" required autofocus>
	    </div>
      </div>
	
	  
	  <button class="btn btn-lg btn-primary btn-block" type="submit">{if $aExist.id}Edit{else}Add{/if}</button>
	</form>
</div>
