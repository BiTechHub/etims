@extends('admin.layouts.master')
@section('main-section')

<link rel="stylesheet" href="{{ asset('assets/summernote/summernote-lite.css') }}">
<div class="container">
  <div class="page-inner">
    <div class="page-header">
      <h3 class="fw-bold mb-3">Add Feedback</h3>
      <ul class="breadcrumbs mb-3">
        <li class="nav-home"><a href="#"><i class="icon-home"></i></a></li>
        <li class="separator"><i class="icon-arrow-right"></i></li>
        <li class="nav-item"><a href="#">Master</a></li>
        <li class="separator"><i class="icon-arrow-right"></i></li>
        <li class="nav-item"><a href="#">Add Feedback</a></li>
      </ul>
    </div>

    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-body">
            <form action="{{ route('master.feedback.store') }}" method="POST">
              @csrf
              <div id="menu-container">
                <!-- Dynamic menu items go here -->
              </div>

              <button type="button" id="add-menu" class="btn-action btn-primary mb-3">
                <i class="fas fa-plus"></i> Add Main Menu For Feedback
              </button>

              <button type="submit" class="btn-action btn-primary">Save All Feedback</button>
            </form>
          </div>
        </div>

        <!-- Menu Template -->
        <div id="menu-template" style="display: none;">
          <div class="menu-item" data-menu-index="__INDEX__" style="margin-bottom: 20px; border: 1px solid #ccc; border-radius: 10px; padding: 15px;">
            <div style="display: flex; justify-content: space-between; flex-wrap: wrap; gap: 10px; align-items: center;">
              <input type="text" name="menus[__INDEX__][name]" placeholder="Menu Name" class="form-control" required style="flex: 1; padding: 10px;">
              <button type="button" class="btn-action btn-danger remove-menu">
                <i class="fas fa-times"></i>
              </button>
            </div>

            <div class="submenu-container" data-menu-id="__INDEX__" style="margin-top: 15px;">
              <!-- Submenus will be appended here -->
            </div>

            <button type="button" class="btn-action btn-primary add-submenu mt-2" data-menu-id="__INDEX__">
              <i class="fas fa-plus"></i> Add Submenu
            </button>
          </div>
        </div>

        <!-- Submenu Template (Question Removed) -->
        <div id="submenu-template" style="display: none;">
          <div class="submenu-item" data-submenu-index="__SUBINDEX__" style="border: 1px solid #ccc; padding: 10px; border-radius: 8px; margin-bottom: 10px;">
            <div style="display: flex; align-items: center; gap: 10px;">
              <input type="text" name="menus[__MENUINDEX__][submenus][__SUBINDEX__][name]" placeholder="Submenu Name" class="form-control" required style="flex: 1; padding: 10px;">
              <button type="button" class="btn-action btn-danger remove-submenu">
                <i class="fas fa-times"></i>
              </button>
            </div>

            <div class="mt-2">
              <label><strong>Response Type</strong></label><br>
              <label>
                <input type="radio" name="menus[__MENUINDEX__][submenus][__SUBINDEX__][response_type]" value="Yes/No" required>
                Yes / No
              </label>
              <label class="ms-3">
                <input type="radio" name="menus[__MENUINDEX__][submenus][__SUBINDEX__][response_type]" value="Rating" required>
                Rating (1-5)
              </label>
              <label class="ms-3">
                <input type="radio" name="menus[__MENUINDEX__][submenus][__SUBINDEX__][response_type]" value="Text" required>
                Text
              </label>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>
@endsection

@section('script')
<script src="{{ url('/admin') }}/assets/js/plugin/datatables/datatables.min.js"></script>
<script src="{{ url('/admin') }}/assets/js/kaiadmin.min.js"></script>
<script src="{{ url('/admin') }}/assets/js/setting-demo2.js"></script>
<script src="{{ asset('assets/summernote/summernote-lite.js') }}"></script>

<script>
  $(document).ready(function () {
    let menuCounter = 0;
    let submenuCounters = {};

    $('#add-menu').click(function () {
      const template = $('#menu-template').html();
      const html = template.replace(/__INDEX__/g, menuCounter);
      $('#menu-container').append(html);
      submenuCounters[menuCounter] = 0;
      menuCounter++;
    });

    $(document).on('click', '.add-submenu', function () {
      const menuId = $(this).data('menu-id');
      const container = $(this).siblings('.submenu-container');
      const template = $('#submenu-template').html();

      const html = template
        .replace(/__MENUINDEX__/g, menuId)
        .replace(/__SUBINDEX__/g, submenuCounters[menuId]);

      container.append(html);
      submenuCounters[menuId]++;
    });

    $(document).on('click', '.remove-menu', function () {
      $(this).closest('.menu-item').remove();
    });

    $(document).on('click', '.remove-submenu', function () {
      $(this).closest('.submenu-item').remove();
    });

    // Load first menu by default
    $('#add-menu').click();
  });
</script>
@endsection
