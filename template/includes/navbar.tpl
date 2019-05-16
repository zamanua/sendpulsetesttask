
<nav class="navbar navbar-expand-lg fixed-top navbar-dark bg-dark">
  <a class="navbar-brand mr-auto mr-lg-0" href="/">TODO</a>
  <button class="navbar-toggler p-0 border-0" type="button" data-toggle="offcanvas">
    <span class="navbar-toggler-icon"></span>
  </button>
  
  <div class="navbar-collapse offcanvas-collapse" id="navbarsExampleDefault">
    <ul class="navbar-nav mr-auto">
{if $bAuth}
      <li class="nav-item {if $smarty.request.action=='/list'}active{/if}">
        <a class="nav-link" href="/list">List</a>
      </li>
      <li class="nav-item {if $smarty.request.action=='/list_add'}active{/if}">
        <a class="nav-link" href="/list_add">Add new</a>
      </li>
      {*<li class="nav-item {if $smarty.request.action=='/user_profile'}active{/if}">
        <a class="nav-link" href="/user_profile">Profile</a>
      </li>*}
      <li class="nav-item {if $smarty.request.action=='/user_logout'}active{/if}">
        <a class="nav-link" href="/user_logout">LogOut</a>
      </li>
{else}
     <li class="nav-item {if $smarty.request.action=='/user_registration'}active{/if}">
        <a class="nav-link" href="/user_registration">user_registration</a>
      </li>
{/if}
    </ul>
  </div>
</nav>