# TODO: Enable Warga Users to Input and Edit Their Own Data from Profile

## Overview
Allow users with the role "warga" to input and edit their own DataWarga data from their profile page.

## Steps

### 1. Update Permissions for Warga Role
- [ ] Update `database/seeders/RoleSeeder.php` to add 'datawarga.create' and 'datawarga.edit' permissions to warga role

### 2. Extend ProfileController for Warga Data
- [ ] Modify `app/Http/Controllers/Settings/ProfileController.php` to include methods for showing and updating warga data linked to authenticated user
- [ ] Add logic to check if user has warga role and has associated DataWarga record
- [ ] Create methods to handle warga data input/edit from profile

### 3. Update DataWarga Model and Controller
- [ ] Add relationship between User and DataWarga models (if not exists)
- [ ] Modify `app/Http/Controllers/DataWargaController.php` to allow warga users to manage their own data
- [ ] Add middleware to ensure users can only edit their own data

### 4. Update Frontend Components
- [ ] Update `resources/js/pages/settings/Profile.vue` to include warga data input/edit form for warga users
- [ ] Create or modify Vue components to handle warga data input and editing
- [ ] Ensure form validation and proper data submission

### 5. Update Routes
- [ ] Modify `routes/settings.php` to include routes for warga data management if needed
- [ ] Ensure routes are protected with appropriate permissions

### 6. Testing and Validation
- [ ] Test the flow for warga users to input new data
- [ ] Test editing existing warga data from profile
- [ ] Ensure proper validation and error handling
- [ ] Verify permissions work correctly

## Dependent Files
- `database/seeders/RoleSeeder.php`
- `app/Http/Controllers/Settings/ProfileController.php`
- `app/Http/Controllers/DataWargaController.php`
- `app/Models/DataWarga.php`
- `app/Models/User.php`
- `resources/js/pages/settings/Profile.vue`
- `routes/settings.php`

## Notes
- Ensure users can only edit their own warga data
- Maintain existing admin functionality for managing all warga data
- Add proper validation for warga data input
