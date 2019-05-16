<!doctype html>
<html lang="en">
  <head>
    {include file='includes/header.tpl'}
  </head>
  <body  {if $smarty.request.action=='/'}class="text-center"{else} role="main" class="container"{/if} >
    {include file='includes/navbar.tpl'}

    {$sContent}

    {include file='includes/footer.tpl'}
    {include file='includes/scripts.tpl'}
  </body>
</html>