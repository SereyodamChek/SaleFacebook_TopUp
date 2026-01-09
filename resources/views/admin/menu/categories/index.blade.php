@extends('layouts.admin')

@section('page_title', 'Menu Categories')

@section('top_actions')
  <a href="#create-category" class="btn-primary" style="text-decoration:none;">
    <i class="fa-solid fa-plus"></i> Add Category
  </a>
@endsection

@section('admin_content')

<div class="box" style="box-shadow:none;">
  <h3 id="create-category">Create Category</h3>
  <p>Categories are the column titles inside mega menu (Example: EMAIL, FACEBOOK, TOOL...).</p>

  <form method="POST" action="{{ route('admin.menu.categories.store') }}" style="margin-top:14px;">
    @csrf

    <div class="form-grid">
      <div class="field">
        <label class="label">Group Key</label>
        <select name="group_key" class="input" required>
          <option value="product">product</option>
          {{-- <option value="recharge">recharge</option>
          <option value="association">association</option> --}}
        </select>
      </div>

      <div class="field">
        <label class="label">Title</label>
        <input name="title" class="input" placeholder="E-MAIL" value="{{ old('title') }}" required>
        @error('title') <div class="error">{{ $message }}</div> @enderror
      </div>

      <div class="field">
        <label class="label">Sort</label>
        <input type="number" name="sort" class="input" value="{{ old('sort', 0) }}">
      </div>

      <div class="field" style="flex-direction:row; align-items:center; gap:10px; margin-top:26px;">
        <input type="checkbox" id="is_active" name="is_active" value="1" checked>
        <label for="is_active" style="font-weight:900;">Active</label>
      </div>
    </div>

    <div class="form-actions">
      <button class="btn-primary" type="submit">
        <i class="fa-solid fa-floppy-disk"></i> Save
      </button>
    </div>
  </form>
</div>

<div class="box" style="box-shadow:none; margin-top:14px;">
  <h3>All Categories</h3>
  <p>Click “Items” to manage menu items under that category.</p>

  <table class="table" style="margin-top:14px;">
    <thead>
      <tr>
        <th>#</th>
        <th>Group</th>
        <th>Title</th>
        <th>Sort</th>
        <th>Status</th>
        <th style="width:220px;">Actions</th>
      </tr>
    </thead>
    <tbody>
      @forelse($categories as $cat)
        <tr>
          <td>{{ $loop->iteration }}</td>
          <td><span class="pill">{{ $cat->group_key }}</span></td>
          <td style="font-weight:900;">{{ $cat->title }}</td>
          <td>{{ $cat->sort }}</td>
          <td>
            @if($cat->is_active)
              <span class="pill" style="background:#ecfdf5;color:#047857;">Active</span>
            @else
              <span class="pill" style="background:#fef2f2;color:#b91c1c;">Disabled</span>
            @endif
          </td>

          <td>
            <div class="admin-row" style="gap:8px;">

              <a class="btn-ghost icon-btn"
                 title="Manage items"
                 href="{{ route('admin.menu.categories.items.index', $cat->id) }}">
                <i class="fa-solid fa-list"></i>
              </a>

              {{-- Edit (Modal) --}}
              <button type="button"
                      class="btn-primary icon-btn js-edit-category"
                      title="Edit"
                      data-update-url="{{ route('admin.menu.categories.update', $cat->id) }}"
                      data-title="{{ $cat->title }}"
                      data-sort="{{ $cat->sort }}"
                      data-group_key="{{ $cat->group_key }}"
                      data-is_active="{{ $cat->is_active ? 1 : 0 }}">
                <i class="fa-solid fa-pen"></i>
              </button>

              {{-- Delete --}}
              <form method="POST"
                    action="{{ route('admin.menu.categories.destroy', $cat->id) }}"
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
          <td colspan="6"><span class="pill">No categories</span></td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>

@endsection


{{-- ===========================
   EDIT CATEGORY MODAL (outside admin content)
=========================== --}}
@push('modals')
<div class="admin-modal" id="editCategoryModal" style="display:none;">
  <div class="admin-modal-backdrop"></div>

  <div class="admin-modal-card">
    <div class="admin-modal-header">
      <h3 style="margin:0;">Edit Category</h3>
      <button type="button" class="icon-btn" id="btnCloseEditCategoryModal" title="Close">
        <i class="fa-solid fa-xmark"></i>
      </button>
    </div>

    <form method="POST" id="editCategoryForm" action="#">
      @csrf
      @method('PATCH')

      <div class="admin-modal-body">
        <div class="form-grid" style="margin-top:0;">

          <div class="field">
            <label class="label">Group Key</label>
            <select name="group_key" id="edit_group_key" class="input" required>
               <option value="product">product</option>
             {{-- <option value="recharge">recharge</option>
              <option value="association">association</option> --}}
            </select>
          </div>

          <div class="field">
            <label class="label">Title</label>
            <input name="title" id="edit_title" class="input" required>
          </div>

          <div class="field">
            <label class="label">Sort</label>
            <input type="number" name="sort" id="edit_sort" class="input" min="0">
          </div>

          <div class="field" style="flex-direction:row; align-items:center; gap:10px; margin-top:26px;">
            <input type="checkbox" id="edit_is_active" name="is_active" value="1">
            <label for="edit_is_active" style="font-weight:900;">Active</label>
          </div>

        </div>
      </div>

      <div class="admin-modal-footer">
        <button type="button" class="btn" id="btnCancelEditCategoryModal">Cancel</button>
        <button type="submit" class="btn-primary">
          <i class="fa-solid fa-floppy-disk"></i> Update
        </button>
      </div>
    </form>
  </div>
</div>

<style>
.admin-modal { position: fixed; inset: 0; z-index: 99999; }
.admin-modal-backdrop { position: absolute; inset: 0; background: rgba(0,0,0,.45); }

.admin-modal-card{
  position: relative;
  width: min(640px, calc(100% - 24px));
  margin: 60px auto;
  background:#fff;
  border-radius:10px;
  overflow:hidden;
  box-shadow: 0 10px 30px rgba(0,0,0,.18);
}

.admin-modal-header, .admin-modal-footer{
  padding:14px 16px;
  display:flex;
  align-items:center;
  justify-content:space-between;
  gap:10px;
}

.admin-modal-body{ padding:16px; }
</style>
@endpush


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

  // ---------- Edit Category Modal ----------
  const modal = document.getElementById('editCategoryModal');
  const backdrop = modal.querySelector('.admin-modal-backdrop');
  const form = document.getElementById('editCategoryForm');

  const fGroup = document.getElementById('edit_group_key');
  const fTitle = document.getElementById('edit_title');
  const fSort  = document.getElementById('edit_sort');
  const fActive= document.getElementById('edit_is_active');

  function openModal(){ modal.style.display = 'block'; }
  function closeModal(){ modal.style.display = 'none'; }

  document.querySelectorAll('.js-edit-category').forEach(btn => {
    btn.addEventListener('click', function(e){
      e.preventDefault();

      form.action = this.dataset.updateUrl;
      fGroup.value = this.dataset.group_key || 'product';
      fTitle.value = this.dataset.title || '';
      fSort.value  = this.dataset.sort ?? 0;
      fActive.checked = (this.dataset.is_active == 1);

      openModal();
    });
  });

  document.getElementById('btnCloseEditCategoryModal').addEventListener('click', function(e){
    e.preventDefault();
    closeModal();
  });

  document.getElementById('btnCancelEditCategoryModal').addEventListener('click', function(e){
    e.preventDefault();
    closeModal();
  });

  backdrop.addEventListener('click', function(){
    closeModal();
  });

  // ---------- Delete confirm (SweetAlert2) ----------
  document.querySelectorAll('.js-swal-delete button').forEach(btn => {
    btn.addEventListener('click', function (e) {
      e.preventDefault();
      const form = this.closest('form');

      Swal.fire({
        title: 'Delete category?',
        text: 'This will also delete its menu items.',
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
