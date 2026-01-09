@extends('layouts.admin')

@section('page_title', 'Menu Items')

@section('top_actions')
  <a href="{{ route('admin.menu.categories.index') }}" class="btn" style="text-decoration:none;">
    ← Back
  </a>

  <a href="#create-item" class="btn-primary" style="text-decoration:none;">
    <i class="fa-solid fa-plus"></i> Add Item
  </a>
@endsection

@section('admin_content')

<div class="box" style="box-shadow:none;">
  <h3 id="create-item">Create Item ({{ $category->title }})</h3>
  <p>Add links/products inside this category for the mega menu.</p>

  <form method="POST"
        action="{{ route('admin.menu.categories.items.store', $category->id) }}"
        enctype="multipart/form-data"
        style="margin-top:14px;">
    @csrf

    <div class="form-grid">
      <div class="field">
        <label class="label">Title</label>
        <input name="title" class="input" value="{{ old('title') }}" required>
        @error('title') <div class="error">{{ $message }}</div> @enderror
      </div>

      <div class="field">
        <label class="label">URL</label>
        <input name="url" class="input" value="{{ old('url') }}">
        @error('url') <div class="error">{{ $message }}</div> @enderror
      </div>

      <div class="field">
        <label class="label">Icon Image (optional)</label>
        <input type="file" name="icon" class="input" accept="image/*">
        @error('icon') <div class="error">{{ $message }}</div> @enderror
        <div style="opacity:.7; font-size:12px; margin-top:6px;">
          Recommended: PNG/SVG, 32x32 or 48x48.
        </div>
      </div>

      <div class="field">
        <label class="label">Status (optional)</label>
        <input name="status" class="input" placeholder="New / Hot / Sale ..." value="{{ old('status') }}">
      </div>

      <div class="field">
        <label class="label">Status Type</label>
        <select name="status_type" class="input">
          <option value="">none</option>
          <option value="success" {{ old('status_type')==='success'?'selected':'' }}>success</option>
          <option value="warning" {{ old('status_type')==='warning'?'selected':'' }}>warning</option>
          <option value="danger"  {{ old('status_type')==='danger'?'selected':''  }}>danger</option>
          <option value="info"    {{ old('status_type')==='info'?'selected':''    }}>info</option>
        </select>
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
  <h3>All Items</h3>
  <p>Manage items under: <span class="pill">{{ $category->title }}</span></p>

  <table class="table" style="margin-top:14px;">
    <thead>
      <tr>
        <th>#</th>
        <th>Icon</th>
        <th>Title</th>
        <th>URL</th>
        <th>Sort</th>
        <th>Status</th>
        <th>Active</th>
        <th style="width:220px;">Actions</th>
      </tr>
    </thead>
    <tbody>
      @forelse($items as $item)
        <tr>
          <td>{{ $loop->iteration }}</td>

          <td style="width:60px;">
            @if($item->icon)
              <img src="{{ asset('storage/'.$item->icon) }}"
                   alt="icon"
                   style="width:28px;height:28px;object-fit:contain;border-radius:6px;">
            @else
              <span style="opacity:.6;">-</span>
            @endif
          </td>

          <td style="font-weight:900;">{{ $item->title }}</td>

          <td style="max-width:320px; word-break:break-word;">
            @if($item->url)
              <a href="{{ $item->url }}" target="_blank">{{ $item->url }}</a>
            @else
              <span style="opacity:.6;">-</span>
            @endif
          </td>

          <td>{{ $item->sort }}</td>

          <td>
            @if($item->status)
              <span class="pill">{{ $item->status }} {{ $item->status_type ? '(' . $item->status_type . ')' : '' }}</span>
            @else
              <span style="opacity:.6;">-</span>
            @endif
          </td>

          <td>
            @if($item->is_active)
              <span class="pill" style="background:#ecfdf5;color:#047857;">Yes</span>
            @else
              <span class="pill" style="background:#fef2f2;color:#b91c1c;">No</span>
            @endif
          </td>

          <td>
            <div class="admin-row" style="gap:8px;">

              <button type="button"
                      class="btn-primary icon-btn js-edit-item"
                      title="Edit"
                      data-update-url="{{ route('admin.menu.categories.items.update', ['category' => $category->id, 'item' => $item->id]) }}"
                      data-title="{{ $item->title }}"
                      data-url="{{ $item->url }}"
                      data-sort="{{ $item->sort }}"
                      data-status="{{ $item->status }}"
                      data-status_type="{{ $item->status_type }}"
                      data-is_active="{{ $item->is_active ? 1 : 0 }}"
                      data-icon="{{ $item->icon ? asset('storage/'.$item->icon) : '' }}">
                <i class="fa-solid fa-pen"></i>
              </button>

              <form method="POST"
                    action="{{ route('admin.menu.categories.items.destroy', ['category' => $category->id, 'item' => $item->id]) }}"
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
          <td colspan="8"><span class="pill">No items</span></td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>

@endsection


{{-- ===========================
   MODAL OUTSIDE CONTENT
=========================== --}}
@push('modals')

<div class="admin-modal" id="editItemModal" style="display:none;">
  <div class="admin-modal-backdrop"></div>

  <div class="admin-modal-card">
    <div class="admin-modal-header">
      <h3 style="margin:0;">Edit Item</h3>
      <button type="button" class="icon-btn" id="btnCloseEditItemModal" title="Close">
        <i class="fa-solid fa-xmark"></i>
      </button>
    </div>

    <form method="POST" id="editItemForm" action="#" enctype="multipart/form-data">
      @csrf
      @method('PATCH')

      <div class="admin-modal-body">
        <div class="form-grid" style="margin-top:0;">
          <div class="field">
            <label class="label">Title</label>
            <input name="title" id="edit_title" class="input" required>
          </div>

          <div class="field">
            <label class="label">URL</label>
            <input name="url" id="edit_url" class="input" placeholder="https://...">
          </div>

          <div class="field">
            <label class="label">Icon Image (optional)</label>
            <input type="file" name="icon" class="input" accept="image/*">
            <div style="margin-top:8px; display:flex; align-items:center; gap:10px;">
              <img id="edit_icon_preview"
                   src=""
                   alt="preview"
                   style="width:32px;height:32px;object-fit:contain;border-radius:6px;display:none;">
              <span id="edit_icon_none" style="opacity:.6;">No icon</span>
            </div>
          </div>

          <div class="field">
            <label class="label">Status (optional)</label>
            <input name="status" id="edit_status" class="input" placeholder="New / Hot / Sale ...">
          </div>

          <div class="field">
            <label class="label">Status Type</label>
            <select name="status_type" id="edit_status_type" class="input">
              <option value="">none</option>
              <option value="success">success</option>
              <option value="warning">warning</option>
              <option value="danger">danger</option>
              <option value="info">info</option>
            </select>
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
        <button type="button" class="btn" id="btnCancelEditItemModal">Cancel</button>
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

  // ---------- Edit Modal ----------
  const editModal = document.getElementById('editItemModal');
  const editForm = document.getElementById('editItemForm');

  const editTitle = document.getElementById('edit_title');
  const editUrl = document.getElementById('edit_url');
  const editSort = document.getElementById('edit_sort');
  const editStatus = document.getElementById('edit_status');
  const editStatusType = document.getElementById('edit_status_type');
  const editIsActive = document.getElementById('edit_is_active');

  const previewImg = document.getElementById('edit_icon_preview');
  const previewNone = document.getElementById('edit_icon_none');

  const backdrop = editModal.querySelector('.admin-modal-backdrop');

  function openEditModal() { editModal.style.display = 'block'; }
  function closeEditModal() { editModal.style.display = 'none'; }

  document.querySelectorAll('.js-edit-item').forEach(btn => {
    btn.addEventListener('click', function (e) {
      e.preventDefault();

      editForm.action = this.dataset.updateUrl;

      editTitle.value = this.dataset.title || '';
      editUrl.value = this.dataset.url || '';
      editSort.value = this.dataset.sort ?? 0;
      editStatus.value = this.dataset.status || '';
      editStatusType.value = this.dataset.status_type || '';
      editIsActive.checked = (this.dataset.is_active == 1);

      const iconUrl = this.dataset.icon || '';
      if (iconUrl) {
        previewImg.src = iconUrl;
        previewImg.style.display = 'inline-block';
        previewNone.style.display = 'none';
      } else {
        previewImg.src = '';
        previewImg.style.display = 'none';
        previewNone.style.display = 'inline';
      }

      openEditModal();
    });
  });

  document.getElementById('btnCloseEditItemModal').addEventListener('click', function(e){
    e.preventDefault();
    closeEditModal();
  });

  document.getElementById('btnCancelEditItemModal').addEventListener('click', function(e){
    e.preventDefault();
    closeEditModal();
  });

  backdrop.addEventListener('click', function(){
    closeEditModal();
  });

  // ---------- Delete confirm (SweetAlert2) ----------
  document.querySelectorAll('.js-swal-delete button').forEach(btn => {
    btn.addEventListener('click', function (e) {
      e.preventDefault();
      const form = this.closest('form');

      Swal.fire({
        title: 'Delete item?',
        text: 'This menu item will be permanently deleted.',
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
