@extends('admin.layouts.master')

@section('main-section')

  <link rel="stylesheet" href="{{ asset('assets/fontawesome/css/all.min.css') }}">

@php
    $admin = Auth::guard('admin')->user();
@endphp

@if($admin->hasAccess('budget', 'view'))

<div class="container">

    <div class="page-inner">

        <div class="page-header">
            <h3 class="fw-bold mb-3">Budget</h3>
            <ul class="breadcrumbs mb-3">
                <li class="nav-home"><a href="#"><i class="icon-home"></i></a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="#">Budget</a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="#">Budget</a></li>
            </ul>
        </div>

        <div class="row">

            {{-- ========== CREATE FORM — sirf ADD access wale dekhenge ========== --}}
            @if($admin->hasAccess('budget', 'add'))
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title"><i class="fas fa-plus-circle me-2"></i>Create Budget</h4>
                    </div>
                    <div class="card-body">

                        <form action="{{ route('admin.budgets.store') }}" method="POST" id="budget-form">
                            @csrf

                            <div style="margin-bottom: 20px;">
                                <div style="width: 50%;">
                                    <div style="position: relative;">
                                        <select id="programme" name="programme_id" style="width: 100%; padding: 15px; background-color: #fff; border: 2px solid #e9ecef; border-radius: 10px;" required>
                                            <option value="">-- Select Programme --</option>
                                            @foreach($programmes as $programme)
                                            <option value="{{ $programme->id }}">{{ $programme->title }}</option>
                                            @endforeach
                                        </select>
                                        <label style="position: absolute; top: -10px; left: 10px; background: white; padding: 0 5px; font-weight: bold; color: #6c757d;">
                                            <i class="fas fa-gamepad me-1"></i> Select Programme
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div id="programme-info" style="margin-bottom: 20px; display: none;">
                                <div style="border-radius: 12px; background: linear-gradient(to right, #f8f9fa, #ffffff); box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
                                    <div style="padding: 15px; border-bottom: 1px solid rgba(0,0,0,0.05); background: rgba(110, 72, 170, 0.05);">
                                        <h5 style="margin: 0; color: #007bff;">
                                            <i class="fas fa-info-circle me-2"></i>Programme Stats
                                        </h5>
                                    </div>
                                    <div style="padding: 20px;">
                                        <div style="display: flex; flex-wrap: wrap; gap: 20px;">
                                            <div style="flex: 1; min-width: 250px;">
                                                <div class="stat-item" style="margin-bottom: 15px;">
                                                    <span class="stat-label"><i class="fas fa-heading me-2"></i>Title:</span>
                                                    <span id="prog-title" class="badge" style="background-color: #007bff;">Not selected</span>
                                                </div>
                                                <div class="stat-item" style="margin-bottom: 15px;">
                                                    <span class="stat-label"><i class="fas fa-clock me-2"></i>Duration:</span>
                                                    <span id="prog-duration" class="badge" style="background-color: #17a2b8;">-</span>
                                                </div>
                                            </div>
                                            <div style="flex: 1; min-width: 250px;">
                                                <div class="stat-item" style="margin-bottom: 15px;">
                                                    <span class="stat-label"><i class="fas fa-users me-2"></i>Strength:</span>
                                                    <span id="prog-strength" class="badge" style="background-color: #28a745;">0</span>
                                                </div>
                                                <div class="stat-item" style="margin-bottom: 15px;">
                                                    <span class="stat-label"><i class="fas fa-map-marker-alt me-2"></i>Venue:</span>
                                                    <span id="prog-venue" class="badge" style="background-color: #ffc107; color: #212529;">-</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="stat-item">
                                            <span class="stat-label"><i class="fas fa-calendar-alt me-2"></i>Dates:</span>
                                            <span id="prog-dates" class="badge" style="background-color: #6c757d;">-</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div style="margin-bottom: 20px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
                                <div style="padding: 15px; background: rgba(110, 72, 170, 0.05);">
                                    <h5 style="margin: 0;"><i class="fas fa-cubes me-2"></i>Budget Items Inventory</h5>
                                </div>
                                <div style="padding: 20px;">
                                    <div style="display: flex; flex-wrap: wrap; gap: 15px;">
                                        <div style="flex: 1; min-width: 250px;">
                                            <div style="position: relative;">
                                                <select id="menu-select" style="width: 100%; padding: 15px; border: 1px solid #ced4da; border-radius: 8px;">
                                                    <option value="">-- Select Menu --</option>
                                                    @foreach($menus as $menu)
                                                    <option value="{{ $menu->id }}">{{ $menu->name }}</option>
                                                    @endforeach
                                                </select>
                                                <label style="position: absolute; top: -10px; left: 10px; background: white; padding: 0 5px; font-weight: bold;">
                                                    <i class="fas fa-layer-group me-1"></i> Menu Category
                                                </label>
                                            </div>
                                        </div>
                                        <div style="flex: 1; min-width: 250px;">
                                            <div style="position: relative;">
                                                <select id="submenu-select" style="width: 100%; padding: 15px; border: 1px solid #ced4da; border-radius: 8px;" disabled>
                                                    <option value="">-- Select Submenu --</option>
                                                </select>
                                                <label style="position: absolute; top: -10px; left: 10px; background: white; padding: 0 5px; font-weight: bold;">
                                                    <i class="fas fa-box-open me-1"></i> Item Selection
                                                </label>
                                            </div>
                                        </div>
                                        <div style="flex: 0 0 auto; align-self: flex-end;">
                                            <button type="button" id="add-menu-submenu" style="width: 100%; padding: 15px; border-radius: 10px; background: linear-gradient(135deg, #6e48aa 0%, #9d50bb 100%); color: white; border: none; cursor: pointer;" disabled>
                                                <i class="fas fa-plus-circle me-1"></i> Add to Budget
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div style="margin-bottom: 20px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
                                <div style="padding: 15px; background: rgba(110, 72, 170, 0.05); display: flex; justify-content: space-between; align-items: center;">
                                    <h5 style="margin: 0;">
                                        <i class="fas fa-clipboard-list me-2"></i>Selected Budget Items
                                        <span id="item-count" class="badge" style="background-color: #007bff; margin-left: 10px;">0</span>
                                    </h5>
                                    <div style="display: flex; align-items: center;">
                                        <span style="margin-right: 10px;">Total:</span>
                                        <span class="badge" style="background-color: #28a745; font-size: 1.1rem;">₹<span id="grand-total">0.00</span></span>
                                    </div>
                                </div>
                                <div style="padding: 20px;">
                                    <div id="selected-menu-submenu" style="display: flex; flex-wrap: wrap; gap: 15px;"></div>
                                </div>
                            </div>

                            <div style="text-align: center; margin-top: 30px;">
                                <button type="submit" style="padding: 15px 30px; border-radius: 12px; background: linear-gradient(135deg, #00b09b 0%, #96c93d 100%); color: white; border: none; box-shadow: 0 4px 15px rgba(0, 180, 155, 0.3); cursor: pointer;">
                                    <i class="fas fa-save me-2"></i> CONFIRM BUDGET
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
            @endif

            {{-- ========== BUDGET TABLE — sabko dikhega jinke paas VIEW hai ========== --}}
            <div class="col-md-12" style="margin-top: 30px;">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">
                            <i class="fas fa-list-alt me-2"></i>Budget Records
                        </h4>
                        <div>
                            <span class="badge bg-primary" style="font-size: 0.9rem; padding: 8px 14px;">
                                <i class="fas fa-coins me-1"></i>
                                Grand Total: ₹{{ number_format($budgets->sum('total'), 2) }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body">

                        @if($budgets->isEmpty())
                            <div class="text-center py-5">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No budget records found</h5>
                                <p class="text-muted small">Create a new budget using the form above.</p>
                            </div>
                        @else

                            <div class="table-responsive">
                                <table class="table table-bordered table-hover" style="font-size: 14px; border-collapse: collapse;">

                                    <thead>
                                        <tr style="background: linear-gradient(135deg, #6e48aa 0%, #9d50bb 100%); color: #fff;">
                                            <th style="width: 50px; text-align: center; vertical-align: middle;">#</th>
                                            <th style="vertical-align: middle;">Programme</th>
                                            <th style="vertical-align: middle;">Menu Category</th>
                                            <th style="vertical-align: middle;">Item</th>
                                            <th style="width: 110px; text-align: right; vertical-align: middle;">Unit Price</th>
                                            <th style="width: 80px; text-align: center; vertical-align: middle;">Qty</th>
                                            <th style="width: 130px; text-align: right; vertical-align: middle;">Line Total</th>
                                            <th style="width: 80px; text-align: center; vertical-align: middle;">Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @php
                                            $grouped = $budgets->getCollection()->groupBy('programme_id');
                                            $globalSl = 0;
                                        @endphp

                                        @foreach($grouped as $programmeId => $items)
                                            @php
                                                $prog = $items->first()->programme;
                                                $progSubtotal = $items->sum('total');
                                            @endphp

                                            <tr style="background: #f0edf5;">
                                                <td colspan="8" style="padding: 12px 16px; border-bottom: 2px solid #6e48aa;">
                                                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                                        <div>
                                                            <span style="font-weight: 700; font-size: 15px; color: #6e48aa;">
                                                                <i class="fas fa-graduation-cap me-2"></i>{{ $prog->title ?? 'N/A' }}
                                                            </span>
                                                            <span class="badge bg-secondary ms-2" style="font-size: 11px;">
                                                                ID: {{ $prog->unique_id ?? $programmeId }}
                                                            </span>
                                                        </div>
                                                        <div class="d-flex gap-3 flex-wrap" style="font-size: 13px; color: #555;">
                                                            <span><i class="fas fa-map-marker-alt me-1 text-danger"></i>{{ $prog->venue ?? '-' }}</span>
                                                            <span><i class="fas fa-calendar me-1 text-info"></i>
                                                                {{ $prog->from_date ? \Carbon\Carbon::parse($prog->from_date)->format('d.m.Y') : '-' }}
                                                                –
                                                                {{ $prog->to_date ? \Carbon\Carbon::parse($prog->to_date)->format('d.m.Y') : '-' }}
                                                            </span>
                                                            <span><i class="fas fa-users me-1 text-success"></i>{{ $prog->strength ?? 0 }} pax</span>
                                                            <span class="fw-bold" style="color: #6e48aa;">
                                                                <i class="fas fa-coins me-1"></i>₹ {{ number_format($progSubtotal, 2) }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>

                                            @foreach($items as $item)
                                                @php $globalSl++; @endphp
                                                <tr class="budget-row">
                                                    <td class="text-center text-muted">{{ $globalSl }}</td>
                                                    <td>
                                                        <small class="text-muted d-md-none d-lg-block" style="font-size:11px;">{{ $prog->title ?? '' }}</small>
                                                    </td>
                                                    <td>
                                                        <span class="badge" style="background-color: #eef2ff; color: #4f46e5; font-weight: 600; font-size: 12px; padding: 5px 10px;">
                                                            {{ $item->menu->name ?? 'N/A' }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $item->submenu->name ?? 'N/A' }}</td>
                                                    <td class="text-right font-monospace">₹ {{ number_format($item->price, 2) }}</td>
                                                    <td class="text-center">{{ $item->quantity }}</td>
                                                    <td class="text-right font-monospace fw-bold">₹ {{ number_format($item->total, 2) }}</td>
                                                    <td class="text-center">
                                                        @if($admin->hasAccess('budget', 'delete'))
                                                            <button type="button" class="btn btn-sm btn-outline-danger btn-delete-budget" data-id="{{ $item->id }}" title="Delete">
                                                                <i class="fas fa-trash-alt"></i>
                                                            </button>
                                                        @else
                                                            <span class="text-muted">—</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach

                                            <tr style="background: #faf8fc; border-top: 2px solid #e0d8ed;">
                                                <td colspan="6" style="text-align: right; font-weight: 700; padding: 10px 16px; color: #6e48aa;">
                                                    Subtotal — {{ $prog->title ?? 'N/A' }}
                                                </td>
                                                <td class="text-right font-monospace fw-bold" style="padding: 10px 16px; color: #6e48aa; font-size: 15px;">
                                                    ${{ number_format($progSubtotal, 2) }}
                                                </td>
                                                <td></td>
                                            </tr>

                                        @endforeach
                                    </tbody>

                                    <tfoot>
                                        <tr style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%); color: #fff;">
                                            <td colspan="6" style="text-align: right; font-weight: 700; padding: 14px 16px; font-size: 15px;">
                                                <i class="fas fa-calculator me-2"></i>GRAND TOTAL
                                            </td>
                                            <td class="text-right font-monospace fw-bold" style="padding: 14px 16px; font-size: 17px; letter-spacing: 0.5px;">
                                                ₹ {{ number_format($budgets->sum('total'), 2) }}
                                            </td>
                                            <td></td>
                                        </tr>
                                    </tfoot>

                                </table>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <div class="text-muted" style="font-size: 13px;">
                                    Showing {{ $budgets->firstItem() ?? 0 }} – {{ $budgets->lastItem() ?? 0 }} of {{ $budgets->total() }} records
                                    ({{ $grouped->count() }} programme{{ $grouped->count() > 1 ? 's' : '' }})
                                </div>
                                <div>
                                    {{ $budgets->appends(request()->query())->links() }}
                                </div>
                            </div>

                        @endif

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- Delete Modal — sirf delete access wale ke liye load karo --}}
@if($admin->hasAccess('budget', 'delete'))
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content" style="border: none; border-radius: 16px; overflow: hidden;">
            <div style="background: linear-gradient(135deg, #dc3545, #c82333); padding: 30px 20px; text-align: center;">
                <div style="width: 70px; height: 70px; border-radius: 50%; background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center; margin: 0 auto 15px;">
                    <i class="fas fa-trash-alt fa-2x text-white"></i>
                </div>
                <h5 class="text-white mb-1">Delete Item?</h5>
                <p class="text-white-50 mb-0" style="font-size: 13px;">This action cannot be undone.</p>
            </div>
            <div class="modal-body text-center py-4">
                <p class="mb-4" style="font-size: 14px;">Are you sure you want to remove this budget item?</p>
                <div class="d-flex gap-2 justify-content-center">
                    <button type="button" class="btn btn-light btn-sm px-4" data-bs-dismiss="modal" style="border-radius: 8px;">Cancel</button>
                    <form id="delete-form" method="POST" action="" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm px-4" style="border-radius: 8px;">
                            <i class="fas fa-trash-alt me-1"></i> Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@else
    <div class="container">
        <div class="alert alert-warning mt-4">
            You don't have permission to view the Budget module.
        </div>
    </div>
@endif

@endsection

@section('script')

<script src="{{ url('/admin') }}/assets/js/plugin/datatables/datatables.min.js"></script>
<script src="{{ url('/admin') }}/assets/js/kaiadmin.min.js"></script>
<script src="{{ url('/admin') }}/assets/js/setting-demo2.js"></script>

@if($admin->hasAccess('budget', 'add'))
<script>
 $(document).ready(function () {

    $('#menu-select').change(function () {
        const menuId = $(this).val();
        $('#submenu-select').empty().append('<option value="">-- Select Submenu --</option>');
        if (!menuId) {
            $('#submenu-select').prop('disabled', true);
            $('#add-menu-submenu').prop('disabled', true);
            return;
        }
        $('#submenu-select').prop('disabled', true);
        $('#submenu-select').html('<option value="">Loading items...</option>');
        $.get(`/admin/menus/${menuId}/submenus`, function (submenus) {
            $('#submenu-select').empty().append('<option value="">-- Select Submenu --</option>');
            if (submenus.length === 0) {
                $('#submenu-select').prop('disabled', true);
                $('#add-menu-submenu').prop('disabled', true);
                $('#submenu-select').append('<option value="" disabled>No items available</option>');
            } else {
                submenus.forEach(submenu => {
                    $('#submenu-select').append(`<option value="${submenu.id}">${submenu.name}</option>`);
                });
                $('#submenu-select').prop('disabled', false);
                $('#add-menu-submenu').prop('disabled', true);
            }
        });
    });

    $('#submenu-select').change(function () {
        $('#add-menu-submenu').prop('disabled', !$(this).val());
    });

    $('#add-menu-submenu').click(function () {
        const menuId = $('#menu-select').val();
        const menuText = $('#menu-select option:selected').text();
        const submenuId = $('#submenu-select').val();
        const submenuText = $('#submenu-select option:selected').text();
        if (!menuId || !submenuId) return;

        const timestamp = Date.now();
        const html = `
            <div style="flex: 1; min-width: 300px;">
                <div class="menu-card" data-menu-id="${menuId}" data-submenu-id="${submenuId}" style="margin-bottom: 15px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); padding: 15px; background: white;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                        <div>
                            <h6 style="margin: 0; font-weight: bold; color: #6e48aa;">${menuText}</h6>
                            <small style="color: #6c757d;">${submenuText}</small>
                        </div>
                        <button type="button" class="remove-pair" style="border: none; background: none; color: #dc3545; cursor: pointer; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                        <div style="flex: 1; min-width: 120px;">
                            <label style="display: block; font-size: 12px; color: #6c757d; margin-bottom: 5px;">Unit Price</label>
                            <div style="display: flex;">
                                <span style="padding: 8px; background-color: #f8f9fa; border: 1px solid #ced4da; border-right: none; border-radius: 8px 0 0 8px;">$</span>
                                <input type="number" min="0" step="0.01" name="budgets[${timestamp}][price]" class="price" data-key="${timestamp}" placeholder="0.00" style="flex: 1; padding: 8px; border: 1px solid #ced4da; border-left: none; border-radius: 0 8px 8px 0;" required>
                            </div>
                        </div>
                        <div style="flex: 1; min-width: 80px;">
                            <label style="display: block; font-size: 12px; color: #6c757d; margin-bottom: 5px;">Qty</label>
                            <input type="number" min="0" step="1" name="budgets[${timestamp}][quantity]" class="quantity" data-key="${timestamp}" placeholder="0" style="width: 100%; padding: 8px; border: 1px solid #ced4da; border-radius: 8px;" required>
                        </div>
                        <div style="flex: 1; min-width: 150px;">
                            <label style="display: block; font-size: 12px; color: #6c757d; margin-bottom: 5px;">Line Total</label>
                            <div style="display: flex;">
                                <span style="padding: 8px; background-color: #f8f9fa; border: 1px solid #ced4da; border-right: none; border-radius: 8px 0 0 8px;">$</span>
                                <input type="text" name="budgets[${timestamp}][total]" class="total" data-key="${timestamp}" readonly style="flex: 1; padding: 8px; font-weight: bold; background-color: #f8f9fa; border: 1px solid #ced4da; border-left: none; border-radius: 0 8px 8px 0;">
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="budgets[${timestamp}][menu_id]" value="${menuId}">
                    <input type="hidden" name="budgets[${timestamp}][submenu_id]" value="${submenuId}">
                </div>
            </div>
        `;
        $('#selected-menu-submenu').append(html);
        updateItemCount();
        $('#menu-select').val('');
        $('#submenu-select').empty().append('<option value="">-- Select Submenu --</option>').prop('disabled', true);
        $('#add-menu-submenu').prop('disabled', true);
        $('.menu-card').last().hide().slideDown(300);
    });

    $(document).on('click', '.remove-pair', function () {
        $(this).closest('div[style*="min-width: 300px"]').slideUp(300, function () {
            $(this).remove();
            updateItemCount();
            calculateGrandTotal();
        });
    });

    $(document).on('input', '.price, .quantity', function () {
        const key = $(this).data('key');
        const price = parseFloat($(`.price[data-key="${key}"]`).val()) || 0;
        const qty = parseInt($(`.quantity[data-key="${key}"]`).val()) || 0;
        $(`.total[data-key="${key}"]`).val((price * qty).toFixed(2));
        calculateGrandTotal();
    });

    function updateItemCount() {
        const count = $('.menu-card').length;
        $('#item-count').text(count);
    }

    function calculateGrandTotal() {
        let grandTotal = 0;
        $('.total').each(function () {
            grandTotal += parseFloat($(this).val()) || 0;
        });
        $('#grand-total').text(grandTotal.toFixed(2));
    }

 });
</script>
@endif

<script>
$(document).ready(function() {
    $('.budget-row').on('mouseenter', function () {
        $(this).css('background-color', '#f8f6ff');
        $(this).find('.btn-delete-budget').css('opacity', '1');
    }).on('mouseleave', function () {
        $(this).css('background-color', '');
        $(this).find('.btn-delete-budget').css('opacity', '0.5');
    });

    $('.btn-delete-budget').css('opacity', '0.5').css('transition', 'opacity 0.2s ease');

    $('.btn-delete-budget').on('click', function () {
        const id = $(this).data('id');
        $('#delete-form').attr('action', `/admin/budgets/${id}`);
        const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
        modal.show();
    });
});
</script>

@endsection