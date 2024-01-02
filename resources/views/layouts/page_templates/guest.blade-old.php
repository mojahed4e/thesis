@include('layouts.navbars.navs.guest')
<div class="wrapper wrapper-full-page">
    <div class="page-header {{ $classPage }} header-filter" filter-color="black" style="background-image: url('{{ $pageBackground }}'); background-size: cover; background-position: top center;align-items: center;">
  <!--   you can change the color of the filter page using: data-color="blue | purple | green | orange | red | rose " -->
    @yield('content')
    @include('layouts.footers.guest')
  </div>
</div>
