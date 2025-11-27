# 🔧 Improvement & Bug Fix Roadmap

**Last Updated:** 27 Nov 2025  
**Status:** Planning Phase

---

## 📊 Summary

Total Issues Found: **20**
- 🔴 Critical: 6
- 🟡 High: 8
- 🟠 Medium: 6

---

## 🔴 CRITICAL (Must Fix)

### 1. Rate Limiting - Missing
**Severity:** 🔴 Critical  
**Impact:** Brute force attacks possible  
**Files:** `routes/web.php`, `routes/api.php`

**Issue:**
- No rate limiting on login, register, API endpoints
- Attackers can brute force passwords
- No protection against automated attacks

**Fix:**
```php
// In routes/web.php
Route::post('/login', [LoginController::class, 'store'])
    ->middleware('throttle:5,1'); // 5 attempts per minute

Route::post('/register', [RegisterController::class, 'store'])
    ->middleware('throttle:3,1'); // 3 attempts per minute
```

**Estimated Time:** 15 min  
**Priority:** P0

---

### 2. Soft Delete - User Model
**Severity:** 🔴 Critical  
**Impact:** Data recovery impossible  
**Files:** `app/Models/User.php`, `database/migrations`

**Issue:**
- User deletion is permanent
- Cannot recover deleted admin accounts
- No audit trail for deletions

**Fix:**
```php
// In User model
use SoftDeletes;

class User extends Authenticatable {
    use SoftDeletes;
    
    protected $dates = ['deleted_at'];
}

// In migration
Schema::table('users', function (Blueprint $table) {
    $table->softDeletes();
});
```

**Estimated Time:** 20 min  
**Priority:** P0

---

### 3. Referral Code - Usage Not Incremented on Register
**Severity:** 🔴 Critical  
**Impact:** Referral tracking broken  
**Files:** `app/Http/Controllers/Auth/RegisterController.php`

**Issue:**
- When user registers with referral code, `used_count` not incremented
- Only incremented when admin creates user
- Referral stats are inaccurate

**Fix:**
```php
// In RegisterController::store()
if ($referrer) {
    // Find and increment the referral code
    $referralCode = ReferralCode::where('code', $request->referral_code)->first();
    if ($referralCode && $referralCode->isValid()) {
        $referralCode->incrementUsage();
    }
}
```

**Estimated Time:** 10 min  
**Priority:** P0

---

### 4. Authorization - Missing Policy Checks
**Severity:** 🔴 Critical  
**Impact:** Users can access unauthorized data  
**Files:** `app/Http/Controllers/CommodityController.php`, `TransferController.php`, `DisposalController.php`

**Issue:**
- No authorization checks in commodity, transfer, disposal controllers
- User can access/modify data they shouldn't
- No role-based access control

**Fix:**
```php
// Create policies
php artisan make:policy CommodityPolicy --model=Commodity

// In controller
public function show(Commodity $commodity) {
    $this->authorize('view', $commodity);
    // ...
}
```

**Estimated Time:** 30 min  
**Priority:** P0

---

### 5. Email Lowercase - Inconsistent
**Severity:** 🔴 Critical  
**Impact:** Duplicate emails possible  
**Files:** `app/Http/Controllers/UserController.php`, `Auth/RegisterController.php`

**Issue:**
- Email not always converted to lowercase
- Can create duplicate emails with different cases
- Email uniqueness validation fails

**Fix:**
```php
// Ensure all email inputs are lowercase
$validated['email'] = strtolower(trim($validated['email']));
```

**Estimated Time:** 15 min  
**Priority:** P0

---

### 6. Input Validation - File Upload MIME Type
**Severity:** 🔴 Critical  
**Impact:** Malicious files can be uploaded  
**Files:** `app/Http/Controllers/UserController.php`, `Auth/ProfileController.php`

**Issue:**
- Avatar upload only checks `image` type
- Can upload files with image extension but different content
- No strict MIME type validation

**Fix:**
```php
'avatar' => ['nullable', 'image', 'mimes:jpeg,png,gif,webp', 'max:2048'],
```

**Estimated Time:** 10 min  
**Priority:** P0

---

## 🟡 HIGH (Should Fix Soon)

### 7. Pagination - No Max Limit
**Severity:** 🟡 High  
**Impact:** Memory exhaustion, DoS  
**Files:** `app/Http/Controllers/*` (all index methods)

**Issue:**
- `per_page` parameter can be set to huge numbers
- Can cause memory issues
- No maximum limit enforced

**Fix:**
```php
$perPage = min($request->get('per_page', 15), 100); // Max 100 per page
$items = Model::paginate($perPage);
```

**Estimated Time:** 20 min  
**Priority:** P1

---

### 8. Referral Code - Expiry Validation
**Severity:** 🟡 High  
**Impact:** Invalid expiry dates accepted  
**Files:** `app/Http/Controllers/ReferralCodeController.php`

**Issue:**
- `expires_at` can be set to past dates
- No maximum future date limit
- Validation incomplete

**Fix:**
```php
'expires_at' => ['nullable', 'date', 'after:now', 'before:+1 year'],
```

**Estimated Time:** 10 min  
**Priority:** P1

---

### 9. Phone Number - No Format Validation
**Severity:** 🟡 High  
**Impact:** Invalid phone numbers accepted  
**Files:** `app/Http/Controllers/UserController.php`, `Auth/ProfileController.php`

**Issue:**
- Phone only has `max:20` validation
- Can accept invalid formats
- No regex validation

**Fix:**
```php
'phone' => ['nullable', 'string', 'regex:/^(\+62|0)[0-9]{9,12}$/', 'max:20'],
```

**Estimated Time:** 10 min  
**Priority:** P1

---

### 10. Query Optimization - N+1 Queries
**Severity:** 🟡 High  
**Impact:** Slow page loads  
**Files:** `app/Http/Controllers/UserController.php` (and others)

**Issue:**
- User index loads roles without eager loading
- Each user triggers separate query for roles
- Performance degradation with many users

**Fix:**
```php
// In UserController::index()
$users = User::with(['roles', 'referrer'])->paginate(15);
```

**Estimated Time:** 20 min  
**Priority:** P1

---

### 11. Timestamps - Missing on Some Models
**Severity:** 🟡 High  
**Impact:** No audit trail for changes  
**Files:** `app/Models/*`

**Issue:**
- Some models don't have `created_at`/`updated_at`
- Difficult to track when data changed
- No audit capability

**Fix:**
```php
// Ensure all models have timestamps
public $timestamps = true;
```

**Estimated Time:** 15 min  
**Priority:** P1

---

### 12. Duplicate Security Questions - No Check
**Severity:** 🟡 High  
**Impact:** User can overwrite without warning  
**Files:** `app/Http/Controllers/Auth/ProfileController.php`

**Issue:**
- No check if user already has security question
- Can silently overwrite existing question
- No confirmation dialog

**Fix:**
```php
// Add check before update
if ($user->hasSecurityQuestion()) {
    return back()->withErrors(['security' => 'Anda sudah memiliki pertanyaan keamanan.']);
}
```

**Estimated Time:** 10 min  
**Priority:** P1

---

### 13. Error Handling - Missing Try-Catch
**Severity:** 🟡 High  
**Impact:** Sensitive info exposure  
**Files:** `app/Http/Controllers/*` (critical operations)

**Issue:**
- No exception handling in file operations
- Database errors not caught
- Stack traces exposed in production

**Fix:**
```php
try {
    // File operation
} catch (Exception $e) {
    Log::error('File upload failed', ['error' => $e->getMessage()]);
    return back()->with('error', 'Gagal upload file.');
}
```

**Estimated Time:** 25 min  
**Priority:** P1

---

### 14. CSRF Protection - Audit
**Severity:** 🟡 High  
**Impact:** CSRF attacks possible  
**Files:** `resources/views/**/*.blade.php`

**Issue:**
- Need to verify all forms have `@csrf`
- Modal forms might be missing CSRF token
- AJAX requests need token header

**Fix:**
```blade
<!-- In all forms -->
@csrf

<!-- In AJAX -->
headers: {
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
}
```

**Estimated Time:** 20 min  
**Priority:** P1

---

## 🟠 MEDIUM (Nice to Have)

### 15. Audit Trail - Incomplete Logging
**Severity:** 🟠 Medium  
**Impact:** Limited security monitoring  
**Files:** `app/Http/Controllers/*`

**Issue:**
- Only user management is logged
- Location, category, commodity changes not logged
- No failed login attempts logged

**Fix:**
```php
// Add logging to all CRUD operations
ActivityLog::log('created', "Membuat kategori: {$category->name}");
ActivityLog::log('updated', "Mengubah kategori: {$category->name}");
ActivityLog::log('deleted', "Menghapus kategori: {$category->name}");
```

**Estimated Time:** 30 min  
**Priority:** P2

---

### 16. API Rate Limiting
**Severity:** 🟠 Medium  
**Impact:** API abuse possible  
**Files:** `app/Http/Controllers/Auth/RegisterController.php`

**Issue:**
- `validateReferral` endpoint has no rate limiting
- Can be used to enumerate referral codes
- No protection against automated requests

**Fix:**
```php
Route::get('/validate-referral', [RegisterController::class, 'validateReferral'])
    ->middleware('throttle:10,1'); // 10 requests per minute
```

**Estimated Time:** 10 min  
**Priority:** P2

---

### 17. Failed Login Logging
**Severity:** 🟠 Medium  
**Impact:** No security monitoring  
**Files:** `app/Http/Controllers/Auth/LoginController.php`

**Issue:**
- Failed login attempts not logged
- Difficult to detect brute force attacks
- No security alerts

**Fix:**
```php
// In LoginController
if (!Auth::attempt($credentials)) {
    ActivityLog::log('failed_login', "Percobaan login gagal: {$request->email}");
}
```

**Estimated Time:** 15 min  
**Priority:** P2

---

### 18. Data Encryption - Optional
**Severity:** 🟠 Medium  
**Impact:** Database compromise risk  
**Files:** `app/Models/User.php`

**Issue:**
- Security answers only hashed, not encrypted
- If database compromised, hashes can be cracked
- No additional security layer

**Fix:**
```php
// Use Laravel encryption
use Illuminate\Support\Facades\Crypt;

$encrypted = Crypt::encrypt($answer);
$decrypted = Crypt::decrypt($encrypted);
```

**Estimated Time:** 30 min  
**Priority:** P3 (Optional)

---

### 19. Input Sanitization - Review
**Severity:** 🟠 Medium  
**Impact:** XSS/Injection possible  
**Files:** `app/Http/Controllers/*`

**Issue:**
- Need to review all user inputs
- Some fields might not be properly sanitized
- Blade templates should use `{{ }}` not `{!! !!}`

**Fix:**
```blade
<!-- Use {{ }} for escaping -->
{{ $user->name }}

<!-- Only use {!! !!} for trusted content -->
{!! $trustedHtml !!}
```

**Estimated Time:** 25 min  
**Priority:** P2

---

### 20. Database Backup Strategy
**Severity:** 🟠 Medium  
**Impact:** Data loss risk  
**Files:** `.env`, `config/backup.php`

**Issue:**
- No backup strategy documented
- No automated backups configured
- Risk of total data loss

**Fix:**
```bash
# Install backup package
composer require spatie/laravel-backup

# Configure in config/backup.php
# Setup automated backups via cron
```

**Estimated Time:** 45 min  
**Priority:** P2

---

## 📈 Implementation Plan

### Phase 1 (Week 1) - Critical Fixes
- [ ] Rate limiting
- [ ] Soft delete
- [ ] Referral code usage
- [ ] Authorization policies
- [ ] Email lowercase
- [ ] File upload MIME

**Estimated:** 2-3 hours

### Phase 2 (Week 2) - High Priority
- [ ] Pagination limit
- [ ] Expiry validation
- [ ] Phone validation
- [ ] Query optimization
- [ ] Timestamps
- [ ] Error handling
- [ ] CSRF audit

**Estimated:** 2-3 hours

### Phase 3 (Week 3) - Medium Priority
- [ ] Audit trail
- [ ] API rate limiting
- [ ] Failed login logging
- [ ] Input sanitization
- [ ] Backup strategy

**Estimated:** 2-3 hours

### Phase 4 (Optional) - Nice to Have
- [ ] Data encryption
- [ ] Advanced monitoring
- [ ] Performance optimization

**Estimated:** 3-4 hours

---

## 🚀 Quick Wins (Can Do Today)

1. **File upload MIME validation** - 5 min
2. **Pagination max limit** - 5 min
3. **Referral expiry validation** - 5 min
4. **Phone regex validation** - 5 min
5. **Query eager loading** - 10 min

**Total:** ~30 minutes

---

## 📝 Notes

- All changes should include tests
- Update documentation after each phase
- Get code review before merging
- Test on staging before production
- Monitor performance after changes

---

**Generated:** 27 Nov 2025  
**By:** Code Review Analysis
