<div class="my-3 p-3 bg-white rounded shadow-sm">
    <h6 class="border-bottom border-gray pb-2 mb-0">List TODO</h6>

    
{foreach from=$aDataForTable item=aRow}
    <div class="media text-muted pt-3 border-bottom border-gray">
      {*<img src='/icons/placeholder.svg' width="32" height="32" background="#6f42c1" color="#6f42c1" class="mr-2 rounded" > *}

  	<div class="mr-2 rounded">
		<div class="custom-control custom-checkbox">
          <input type="checkbox" class="custom-control-input" id="same-address-{$aRow.id}">
          <label class="custom-control-label" for="same-address-{$aRow.id}"></label>
        </div>
  	</div>

      <p class="media-body pb-3 mb-0 small lh-125 ">
        <strong class="d-block text-gray-dark">{$aRow.post_date}</strong>
        <strong class="d-block text-gray-dark">{$aRow.name}</strong>
        {$aRow.description}
      </p>


		<div class="mr-2 rounded">
				<a href="/list_edit/{$aRow.id}" class="btn btn-outline-success">Edit</a>
				<a href="#" class="btn btn-primary">Add Sub</a>
				<a href="/list_delete/{$aRow.id}" class="btn btn-secondary">Delete</a>
		</div>

    </div>
{/foreach}


    <small class="d-block text-right mt-3">
      <a href="/list_add">Add New TODO</a>
    </small>
  </div>