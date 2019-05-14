
<form class="form-signin" action="/list_add" method="post">
  <input type="hidden" name="is_post" value="1">

  <h1 class="h3 mb-3 font-weight-normal">Create new TODO</h1>

  <label for="inputName" class="sr-only">Name</label>
  <input name="data[name]" value="{$aExist.name}" type="text" id="inputName" class="form-control" placeholder="name" required autofocus>

  <label for="inputDescription" class="sr-only">Description</label>
  <input name="data[description]" value="{$aExist.description}" type="text" id="inputDescription" class="form-control" placeholder="Description" required autofocus>

{if $aExist.id}
<input type="hidden" name="id" value="{$aExist.id}">
{/if}
  
  <button class="btn btn-lg btn-primary btn-block" type="submit">{if $aExist.id}Edit{else}Add{/if}</button>
</form>
