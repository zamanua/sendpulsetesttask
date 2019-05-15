<div class="my-3 p-3 bg-white rounded shadow-sm">
    <h6 class="border-bottom border-gray pb-2 mb-0">List TODO</h6>

    
{foreach from=$aDataForTable item=aRow}
    <div class="media text-muted pt-3 border-bottom border-gray">

  	<div class="mr-2 rounded">
		<div class="custom-control custom-checkbox">
          <input type="checkbox" class="custom-control-input" id="same-address-{$aRow.id}" {if $aRow.checked==1}checked="checked"{/if}
          	onclick="document.location='{strip}
		{if $aRow.checked==0}
			/list_mark/{$aRow.id}
		{else}
			/list_unmark/{$aRow.id}
		{/if}
          	{/strip}'"
          >
          <label class="custom-control-label" for="same-address-{$aRow.id}"></label>
        </div>
  	</div>

      <p class="media-body pb-3 mb-0 small lh-125 ">
        <strong class="d-block text-gray-dark">{$aRow.post_date}</strong>
        <strong class="d-block text-gray-dark">{$aRow.name}</strong>
        {$aRow.description}
      </p>


		<div class="mr-2 rounded">
		{*if $aRow.checked==0}
			<a href="/list_mark/{$aRow.id}" class="btn btn-success">Mark</a>
		{else}
			<a href="/list_unmark/{$aRow.id}" class="btn btn-info">UnMark</a>
		{/if*}
			<a href="/list_edit/{$aRow.id}" class="btn btn-warning">Edit</a>
			<a href="#" class="btn btn-primary">Add Sub</a>
			<a href="/list_delete/{$aRow.id}" class="btn btn-danger">Delete</a>
		</div>

    </div>
{/foreach}


    <small class="d-block text-right mt-3">
      <a href="/list_add">Add New TODO</a>
    </small>
  </div>