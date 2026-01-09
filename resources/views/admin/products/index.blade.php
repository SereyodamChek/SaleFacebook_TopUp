@extends('layouts.admin')

@section('page_title', 'Products')

@section('top_actions')
  <button class="btn-primary" type="button" id="btnOpenCreateProductModal" style="text-decoration:none;">
    <i class="fa-solid fa-plus"></i> Add Product
  </button>
@endsection

@section('admin_content')


<div class="box" style="box-shadow:none;">
  <h3>All Products</h3>
  <p>Manage products with price, stock, sold amount and description.</p>

  <table class="table" style="margin-top:14px;">
    <thead>
      <tr>
        <th>#</th>
        <th>Title</th>
        <th>Menu Item</th>
        <th>Price</th>
        <th>Stock</th>
        <th>Sold</th>
        <th>Status</th>
        <th style="width:160px;">Actions</th>
      </tr>
    </thead>

    <tbody>
      @forelse($products as $p)
        <tr>
          <td>{{ $loop->iteration }}</td>

          <td style="font-weight:900;">{{ $p->title }}</td>

          <td>
            @if($p->menuItem)
              <span class="pill">
                {{ $p->menuItem->category?->title }} → {{ $p->menuItem->title }}
              </span>
            @else
              <span style="opacity:.6;">-</span>
            @endif
          </td>

          <td>${{ number_format((float)$p->price, 2) }}</td>
          <td>{{ $p->stock }}</td>
          <td>{{ $p->sold_out_amount }}</td>

          <td>
            @if($p->is_active)
              <span class="pill" style="background:#ecfdf5;color:#047857;">Active</span>
            @else
              <span class="pill" style="background:#fef2f2;color:#b91c1c;">Disabled</span>
            @endif
          </td>

          <td>
            <div class="admin-row" style="gap:8px;">
              <button type="button"
                      class="btn-primary icon-btn js-edit-product"
                      title="Edit"
                      data-update-url="{{ route('admin.products.update', $p->id) }}"
                      data-title="{{ $p->title }}"
                      data-menu_item_id="{{ $p->menu_item_id }}"
                      data-price="{{ $p->price }}"
                      data-stock="{{ $p->stock }}"
                      data-sold="{{ $p->sold_out_amount }}"
                      data-description="{{ e($p->description) }}"
                      data-is_active="{{ $p->is_active ? 1 : 0 }}">
                <i class="fa-solid fa-pen"></i>
              </button>

              <form method="POST"
                    action="{{ route('admin.products.destroy', $p->id) }}"
                    class="js-swal-delete">
                @csrf
                @method('DELETE')
                <button class="btn-danger icon-btn" type="button" title="Delete">
                  <i class="fa-solid fa-trash"></i>
                </button>
              </form>
            </div>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="8"><span class="pill">No products</span></td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>

@endsection

{{-- ===========================
   MODALS OUTSIDE CONTENT
   (because layouts.admin renders @stack('modals') outside admin-wrap)
=========================== --}}
@push('modals')

{{-- Create Product Modal --}}
<div class="admin-modal" id="createProductModal" style="display:none;">
  <div class="admin-modal-backdrop"></div>

  <div class="admin-modal-card">
    <div class="admin-modal-header">
      <h3 style="margin:0;">Add Product</h3>
      <button type="button" class="icon-btn" id="btnCloseCreateProductModal" title="Close">
        <i class="fa-solid fa-xmark"></i>
      </button>
    </div>

    <form method="POST" action="{{ route('admin.products.store') }}">
      @csrf

      <div class="admin-modal-body">
        <div class="form-grid" style="margin-top:0;">

          <div class="field">
            <label class="label">Title</label>
            <input name="title" class="input" required>
          </div>

          <div class="field">
            <label class="label">Price</label>
            <input type="number" step="0.01" min="0" name="price" class="input" value="0" required>
          </div>

          <div class="field">
            <label class="label">Stock</label>
            <input type="number" min="0" name="stock" class="input" value="0" required>
          </div>

          <div class="field">
            <label class="label">Sold Out Amount</label>
            <input type="number" min="0" name="sold_out_amount" class="input" value="0">
          </div>

          <div class="field" style="grid-column:1/-1;">
            <label class="label">Description</label>
            <textarea name="description" class="input" rows="4" placeholder="Product details..."></textarea>
          </div>

          <div class="field">
            <label class="label">Link to Mega Menu Item (optional)</label>
            <select name="menu_item_id" class="input">
              <option value="">-- none --</option>
              @foreach($menuItems as $mi)
                <option value="{{ $mi->id }}">
                  {{ $mi->category?->group_key }} / {{ $mi->category?->title }} → {{ $mi->title }}
                </option>
              @endforeach
            </select>
          </div>

          <div class="field" style="flex-direction:row; align-items:center; gap:10px; margin-top:26px;">
            <input type="checkbox" id="create_is_active" name="is_active" value="1" checked>
            <label for="create_is_active" style="font-weight:900;">Active</label>
          </div>

        </div>
      </div>

      <div class="admin-modal-footer">
        <button type="button" class="btn" id="btnCancelCreateProductModal">Cancel</button>
        <button type="submit" class="btn-primary">
          <i class="fa-solid fa-floppy-disk"></i> Save
        </button>
      </div>
    </form>
  </div>
</div>

{{-- Edit Product Modal --}}
<div class="admin-modal" id="editProductModal" style="display:none;">
  <div class="admin-modal-backdrop"></div>

  <div class="admin-modal-card">
    <div class="admin-modal-header">
      <h3 style="margin:0;">Edit Product</h3>
      <button type="button" class="icon-btn" id="btnCloseEditProductModal" title="Close">
        <i class="fa-solid fa-xmark"></i>
      </button>
    </div>

    <form method="POST" id="editProductForm" action="#">
      @csrf
      @method('PATCH')

      <div class="admin-modal-body">
        <div class="form-grid" style="margin-top:0;">

          <div class="field">
            <label class="label">Title</label>
            <input name="title" id="edit_title" class="input" required>
          </div>

          <div class="field">
            <label class="label">Price</label>
            <input type="number" step="0.01" min="0" name="price" id="edit_price" class="input" required>
          </div>

          <div class="field">
            <label class="label">Stock</label>
            <input type="number" min="0" name="stock" id="edit_stock" class="input" required>
          </div>

          <div class="field">
            <label class="label">Sold Out Amount</label>
            <input type="number" min="0" name="sold_out_amount" id="edit_sold" class="input">
          </div>

          <div class="field" style="grid-column:1/-1;">
            <label class="label">Description</label>
            <textarea name="description" id="edit_description" class="input" rows="4"></textarea>
          </div>

          <div class="field">
            <label class="label">Link to Mega Menu Item (optional)</label>
            <select name="menu_item_id" id="edit_menu_item_id" class="input">
              <option value="">-- none --</option>
              @foreach($menuItems as $mi)
                <option value="{{ $mi->id }}">
                  {{ $mi->category?->group_key }} / {{ $mi->category?->title }} → {{ $mi->title }}
                </option>
              @endforeach
            </select>
          </div>

          <div class="field" style="flex-direction:row; align-items:center; gap:10px; margin-top:26px;">
            <input type="checkbox" id="edit_is_active" name="is_active" value="1">
            <label for="edit_is_active" style="font-weight:900;">Active</label>
          </div>

        </div>
      </div>

      <div class="admin-modal-footer">
        <button type="button" class="btn" id="btnCancelEditProductModal">Cancel</button>
        <button type="submit" class="btn-primary">
          <i class="fa-solid fa-floppy-disk"></i> Update
        </button>
      </div>
    </form>
  </div>
</div>

{{-- Modal CSS (scoped, no Bootstrap conflict) --}}
<style>
.admin-modal { position: fixed; inset: 0; z-index: 99999; }
.admin-modal-backdrop { position: absolute; inset: 0; background: rgba(0,0,0,.45); }

.admin-modal-card {
  position: relative;
  width: min(760px, calc(100% - 24px));
  margin: 60px auto;
  background: #fff;
  border-radius: 10px;
  overflow: hidden;
  box-shadow: 0 10px 30px rgba(0,0,0,.18);
}

.admin-modal-header, .admin-modal-footer {
  padding: 14px 16px;
  display:flex;
  align-items:center;
  justify-content:space-between;
  gap:10px;
}

.admin-modal-body { padding: 16px; }

.btn { padding: 10px 14px; border-radius: 8px; border: 1px solid #e5e7eb; background: #fff; cursor:pointer; }
</style>

@endpush


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

  // ---------- Create Modal ----------
  const createModal = document.getElementById('createProductModal');
  const createCard  = createModal.querySelector('.admin-modal-card');
  const createBackdrop = createModal.querySelector('.admin-modal-backdrop');

  function openCreate(){ createModal.style.display = 'block'; }
  function closeCreate(){ createModal.style.display = 'none'; }

  document.getElementById('btnOpenCreateProductModal').addEventListener('click', function(e){
    e.preventDefault();
    openCreate();
  });

  document.getElementById('btnCloseCreateProductModal').addEventListener('click', function(e){
    e.preventDefault();
    closeCreate();
  });

  document.getElementById('btnCancelCreateProductModal').addEventListener('click', function(e){
    e.preventDefault();
    closeCreate();
  });

  // close only when clicking backdrop
  createBackdrop.addEventListener('click', function(){
    closeCreate();
  });

  // prevent click inside modal from closing
  createCard.addEventListener('click', function(e){
    e.stopPropagation();
  });

  // ---------- Edit Modal ----------
  const editModal = document.getElementById('editProductModal');
  const editCard  = editModal.querySelector('.admin-modal-card');
  const editBackdrop = editModal.querySelector('.admin-modal-backdrop');
  const editForm  = document.getElementById('editProductForm');

  const fTitle = document.getElementById('edit_title');
  const fPrice = document.getElementById('edit_price');
  const fStock = document.getElementById('edit_stock');
  const fSold  = document.getElementById('edit_sold');
  const fDesc  = document.getElementById('edit_description');
  const fMenu  = document.getElementById('edit_menu_item_id');
  const fActive= document.getElementById('edit_is_active');

  function openEdit(){ editModal.style.display = 'block'; }
  function closeEdit(){ editModal.style.display = 'none'; }

  document.querySelectorAll('.js-edit-product').forEach(btn => {
    btn.addEventListener('click', function(e){
      e.preventDefault();

      editForm.action = this.dataset.updateUrl;
      fTitle.value = this.dataset.title || '';
      fPrice.value = this.dataset.price || 0;
      fStock.value = this.dataset.stock || 0;
      fSold.value  = this.dataset.sold || 0;
      fDesc.value  = this.dataset.description || '';
      fMenu.value  = this.dataset.menu_item_id || '';
      fActive.checked = (this.dataset.is_active == 1);

      openEdit();
    });
  });

  document.getElementById('btnCloseEditProductModal').addEventListener('click', function(e){
    e.preventDefault();
    closeEdit();
  });

  document.getElementById('btnCancelEditProductModal').addEventListener('click', function(e){
    e.preventDefault();
    closeEdit();
  });

  // close only when clicking backdrop
  editBackdrop.addEventListener('click', function(){
    closeEdit();
  });

  // prevent click inside modal from closing
  editCard.addEventListener('click', function(e){
    e.stopPropagation();
  });

  // ---------- Delete confirm (SweetAlert2) ----------
  document.querySelectorAll('.js-swal-delete button').forEach(btn => {
    btn.addEventListener('click', function (e) {
      e.preventDefault();
      const form = this.closest('form');

      Swal.fire({
        title: 'Delete product?',
        text: 'This product will be permanently deleted.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, delete',
        cancelButtonText: 'Cancel',
        reverseButtons: true
      }).then((r) => {
        if (r.isConfirmed) form.submit();
      });
    });
  });

});
</script>
@endpush
