<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="card">
   <div class="card-header">
      <h3 class="card-title">User Management</h3>
      <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#userModal">
         <i class="fas fa-plus"></i> New User
      </button>
   </div>
   <div class="card-body">
      <?php if (session()->getFlashdata('success')): ?>
      <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
      <?php endif; ?>

      <table class="table table-bordered">
         <thead>
            <tr>
               <th>Username</th>
               <th>Role</th>
               <th>Branch</th>
               <th>Status</th>
               <th>Last Login</th>
               <th>Actions</th>
            </tr>
         </thead>
         <tbody>
            <?php foreach ($users as $user): ?>
            <tr>
               <td><?= $user['username'] ?></td>
               <td><?= ucfirst($user['user_group']) ?></td>
               <td><?= $user['branch_name'] ?? 'N/A' ?></td>
               <td>
                  <?= $user['is_active'] ?
                        '<span class="badge bg-success">Active</span>' :
                        '<span class="badge bg-danger">Inactive</span>' ?>
               </td>
               <!-- <td><?= $user['last_login'] ? date('d/m/Y H:i', strtotime($user['last_login'])) : 'Never' ?></td> -->
               <td></td>
               <td>
                  <button class="btn btn-sm btn-warning edit-user" data-id="<?= $user['id'] ?>"
                     data-username="<?= $user['username'] ?>" data-group="<?= $user['user_group'] ?>"
                     data-branch="<?= $user['branch_code'] ?>" data-status="<?= $user['is_active'] ?>">
                     <i class="fas fa-edit"></i>
                  </button>
                  <a href="<?= site_url('/admin/users/delete/' . $user['id']) ?>" class="btn btn-sm btn-danger"
                     onclick="return confirm('Are you sure?')">
                     <i class="fas fa-trash"></i>
                  </a>
               </td>
            </tr>
            <?php endforeach; ?>
         </tbody>
      </table>
   </div>
</div>

<!-- User Modal -->
<div class="modal fade" id="userModal">
   <div class="modal-dialog">
      <div class="modal-content">
         <form action="<?= site_url('/admin/users/save') ?>" method="post">
            <div class="modal-header">
               <h4 class="modal-title">Manage User</h4>
               <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
               <input type="hidden" name="id">
               <div class="form-group">
                  <label>Username</label>
                  <input type="text" name="username" class="form-control" required>
               </div>
               <div class="form-group">
                  <label>Password</label>
                  <input type="password" name="password" class="form-control" required>
               </div>
               <div class="form-group">
                  <label>User Role</label>
                  <select name="user_group" class="form-control" required>
                     <option value="superadmin">Super Admin</option>
                     <option value="ho_accountant">Head Office Accountant</option>
                     <option value="branch_accountant">Branch Accountant</option>
                     <option value="auditor">Auditor</option>
                  </select>
               </div>
               <div class="form-group">
                  <label>Branch (if applicable)</label>
                  <select name="branch_code" class="form-control">
                     <option value="">Select Branch</option>
                     <?php foreach ($branches as $branch): ?>
                     <option value="<?= $branch['branch_code'] ?>"><?= $branch['name'] ?></option>
                     <?php endforeach; ?>
                  </select>
               </div>
               <div class="form-group">
                  <div class="custom-control custom-switch">
                     <input type="checkbox" class="custom-control-input" name="is_active" id="is_active" value="1"
                        checked>
                     <label class="custom-control-label" for="is_active">Active</label>
                  </div>
               </div>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
               <button type="submit" class="btn btn-primary">Save User</button>
            </div>
         </form>
      </div>
   </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
   // Edit User
   $('.edit-user').click(function() {
      const user = {
         id: $(this).data('id'),
         username: $(this).data('username'),
         user_group: $(this).data('group'),
         branch_code: $(this).data('branch'),
         is_active: $(this).data('status')
      };

      $('#userModal').modal('show');
      $('[name="id"]').val(user.id);
      $('[name="username"]').val(user.username);
      $('[name="user_group"]').val(user.user_group);
      $('[name="branch_code"]').val(user.branch_code);
      $('[name="is_active"]').prop('checked', user.is_active == 1);
      $('[name="password"]').prop('required', false);
   });

   // Reset form on modal close
   $('#userModal').on('hidden.bs.modal', function() {
      $(this).find('form')[0].reset();
      $('[name="password"]').prop('required', true);
   });
});
</script>
<?= $this->endSection() ?>