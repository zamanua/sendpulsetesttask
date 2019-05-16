<div class="my-3 p-3 bg-white rounded shadow-sm">
    <h6 class="border-bottom border-gray pb-2 mb-0">List TODO</h6>

    
{foreach from=$aDataForTable item=aRow}
	<div class="media text-muted pt-3 border-bottom border-gray">
    	
    </div>
    <div class="media text-muted pt-3 border-bottom border-gray">
    	{include file='list/list_item.tpl' aRow=$aRow}
    </div>

	{if $aRow.childs}
		{foreach from=$aRow.childs item=aChild}
		<div class="media text-muted pt-3 border-bottom border-gray">
	    	<div class="mr-2 rounded" style="width: 30px;">&nbsp;</div>{include file='list/list_item.tpl' aRow=$aChild}
	    </div>
		{/foreach}
	{/if}
{/foreach}


    <small class="d-block text-right mt-3">
      <a href="/list_add">Add New TODO</a>
    </small>
  </div>