# Personal Task Manager

Apiato 13 və Laravel 12 əsasında hazırlanmış Personal Task Manager tətbiqi. İstifadəçilər qeydiyyatdan keçə, daxil ola və yalnız öz tapşırıqlarını yarada, siyahılaya, yeniləyə və silə bilərlər.

## Texnologiyalar

- PHP 8.4
- Laravel 12
- Apiato 13 (Porto arxitekturası)
- Laravel Passport 13
- Eloquent ORM
- JavaScript Fetch API
- MySQL/MariaDB (istifadə olunan DB driver `.env` faylında müəyyən edilir)

## WEB və API ünvanları

| Hissə       | Ünvan                       |
| ----------- | --------------------------- |
| WEB tətbiqi | `http://apiato.test`        |
| API bazası  | `http://api.apiato.test/v1` |

WEB və API ayrı subdomain-lərdə işləyir. Lokal mühitdə hər iki host Laragon virtual host/hosts konfiqurasiyasında layihənin `public` qovluğuna yönləndirilməlidir.

## Quraşdırma və işə salma

### Tələblər

- PHP 8.4 və layihənin `composer.json` faylında tələb olunan PHP extension-ları
- Composer
- MySQL və ya layihənin `.env` konfiqurasiyasında seçilmiş uyğun DB serveri
- Laragon və ya uyğun lokal web server

### Addımlar

1. Layihəni lokal mühitə yerləşdirin və layihənin kök qovluğunda terminal açın.
2. PHP asılılıqlarını quraşdırın:

    ```bash
    composer install
    ```

3. `.env.example` faylını `.env` adı ilə köçürün. `.env` faylında tətbiq və verilənlər bazası parametrlərini öz mühitinizə uyğun doldurun. Məxfi dəyərləri repozitoriyaya əlavə etməyin.

4. Tətbiq açarını yaradın:

    ```bash
    php artisan key:generate
    ```

5. Passport açarlarının mövcudluğunu yoxlayın. Tələb olunursa, Passport sənədlərində göstərilən açar yaratma addımını icra edin. Passport private key-i paylaşmayın.

6. Laragon-da aşağıdakı hostları layihənin `public` qovluğuna yönləndirin:
    - `apiato.test`
    - `api.apiato.test`

7. Verilənlər bazasını yaradın və `.env`-də DB bağlantısını konfiqurasiya edin. Sonra migrasiyaları icra edin:

    ```bash
    php artisan migrate
    ```

8. WEB Password Grant üçün Passport client yaradın:

    ```bash
    php artisan passport:client --password --name="Apiato Web Client" --provider=users
    ```

    Komandanın verdiyi Client ID və Client Secret dəyərlərini `.env`-də müvafiq olaraq `CLIENT_WEB_ID` və `CLIENT_WEB_SECRET` parametrlərinə yazın. Secret-i heç kimlə paylaşmayın. Konfiqurasiya cache aktivdirsə, dəyişikliklərdən sonra onu təmizləyin:

    ```bash
    php artisan config:clear
    ```

9. Demo məlumatlarını əlavə edin:

    ```bash
    php artisan db:seed
    ```

10. Tətbiqi brauzerdə açın: `http://apiato.test`.

## Demo məlumatları

`TaskManagementSeeder_1` iki demo istifadəçi və ümumilikdə beş tapşırıq yaradır:

- `taskuser1@example.com` — 3 tapşırıq
- `taskuser2@example.com` — 2 tapşırıq

Seed edilmiş istifadəçilərin parolları burada göstərilmir; lazım olduqda seeder mənbəyindən yoxlayın. `AuthorizationSeeder_1` admin rolunu guard-lar üçün yaradır, `SuperAdminSeeder_2` isə super admin istifadəçisini yaradır. Demo hesablarını production mühitində istifadə etməyin.

## WEB marşrutları

| Method | Path               | Təyinat                                                   |
| ------ | ------------------ | --------------------------------------------------------- |
| GET    | `/`                | Əsas səhifə                                               |
| GET    | `/login`           | Giriş formu                                               |
| POST   | `/login`           | WEB giriş                                                 |
| GET    | `/register`        | Qeydiyyat formu                                           |
| POST   | `/register`        | Qeydiyyat                                                 |
| POST   | `/logout`          | WEB çıxış (`auth:web`)                                    |
| GET    | `/tasks`           | Tapşırıqlar səhifəsi (`auth:web`)                         |
| GET    | `/tasks/create`    | Tapşırıq yaratma səhifəsi (`auth:web`)                    |
| GET    | `/tasks/{id}/edit` | Tapşırıq redaktə səhifəsi (`auth:web`)                    |
| POST   | `/session/refresh` | WEB sessiyasındakı API tokenlərini yeniləyir (`auth:web`) |

WEB formaları Laravel CSRF qorumasından istifadə edir.

## API marşrutları

Bütün API yolları `http://api.apiato.test` hostunda işləyir.

| Method | Path                      | Auth          | Təyinat                      |
| ------ | ------------------------- | ------------- | ---------------------------- |
| POST   | `/v1/clients/web/login`   | Public        | API tokenləri almaq          |
| POST   | `/v1/clients/web/refresh` | Refresh token | Tokenləri yeniləmək          |
| POST   | `/v1/logout`              | `auth:api`    | Cari API tokenini ləğv etmək |
| GET    | `/v1/tasks`               | `auth:api`    | İstifadəçinin tapşırıqları   |
| POST   | `/v1/tasks`               | `auth:api`    | Tapşırıq yaratmaq            |
| GET    | `/v1/tasks/{id}`          | `auth:api`    | Bir tapşırığa baxmaq         |
| PATCH  | `/v1/tasks/{id}`          | `auth:api`    | Tapşırığı qismən yeniləmək   |
| DELETE | `/v1/tasks/{id}`          | `auth:api`    | Tapşırığı silmək             |

Qeydiyyat API endpoint-i layihədə mövcud olsa da, bu sənəddə onun dəqiq URL və request formatı təsdiqlənmədiyi üçün göstərilmir.

### Ümumi API başlıqları

JSON sorğuları üçün:

```http
Accept: application/json
Content-Type: application/json
```

Qorunan endpoint-lər üçün əlavə olaraq:

```http
Authorization: Bearer <access_token>
```

### API Login

`POST /v1/clients/web/login`

```json
{
    "email": "taskuser1@example.com",
    "password": "<your-password>"
}
```

Uğurlu cavabın əsas strukturu:

```json
{
    "data": {
        "token_type": "Bearer",
        "expires_in": 86400,
        "access_token": "<access_token>",
        "refresh_token": "<refresh_token>"
    }
}
```

`expires_in` konfiqurasiyadan asılıdır. API login ayrıca API autentifikasiya axınıdır və WEB sessiyasına avtomatik bağlanmır.

### Refresh

`POST /v1/clients/web/refresh`

```json
{
    "refresh_token": "<refresh_token>"
}
```

Refresh token body-də və ya `refreshToken` cookie-də ötürülə bilər. Uğurlu cavab yeni token cütünü `data` daxilində qaytarır.

### Tapşırıq yaratmaq

`POST /v1/tasks`

```json
{
    "title": "Review project plan",
    "description": "Check milestones",
    "is_completed": false
}
```

- `title` — tələb olunur, maksimum 150 simvol.
- `description` — nullable/optional.
- `is_completed` — optional boolean, default `false`.
- `user_id` client tərəfindən ötürülmür; server onu autentifikasiya olunmuş istifadəçidən götürür.

### Tapşırıqları siyahılamaq və filtrləmək

- `GET /v1/tasks` — cari istifadəçinin bütün tapşırıqları.
- `GET /v1/tasks?is_completed=0` — tamamlanmamış tapşırıqlar.
- `GET /v1/tasks?is_completed=1` — tamamlanmış tapşırıqlar.
- `page` query parametri səhifəni seçir.

Siyahı cavabı `data` və `meta.pagination` strukturlarından ibarətdir. Pagination metadata-ya `total`, `count`, `per_page`, `current_page`, `total_pages` və `links` daxildir.

### Tapşırığı yeniləmək

`PATCH /v1/tasks/{id}`

```json
{
    "title": "Review final project plan",
    "is_completed": true
}
```

Yalnız dəyişdirilən sahələri göndərmək olar. Dəstəklənən sahələr `title`, `description`, `is_completed`-dir.

### Tapşırıq ID-si və sahiblik

API cavabındakı task `id` Apiato Hash ID-dir. Sonrakı GET/PATCH/DELETE sorğularında verilənlər bazasının rəqəmli ID-si deyil, API cavabında gələn hash ID istifadə edilməlidir.

Tapşırıqlar cari istifadəçiyə görə məhdudlaşdırılır. Başqa istifadəçiyə aid tapşırıq ID-si ilə baxış, yeniləmə və silmə cəhdləri `404` qaytarmalıdır. Autentifikasiya olunmamış qorunan API sorğusu `401` qaytarır. Request validation xətaları `422` ola bilər. DELETE uğuru `204 No Content`, API logout uğuru `202`-dir.

## Əlavə araşdırılacaq mövzular

Aşağıdakı izahlar mövzuları layihədəki konkret tətbiqlə əlaqələndirir.

### 1. HTTP, REST və JSON

HTTP client və server arasında sorğu-cavab protokoludur. Method əməliyyatın növünü bildirir: `GET` məlumat oxuyur, `POST` resurs yaradır və ya əməliyyat başladır, `PATCH` mövcud resursun bir hissəsini dəyişir, `DELETE` resursu silir. Header metadata və autentifikasiya məlumatı daşıyır; body göndərilən məlumatdır. Status code nəticəni bildirir: `200` uğur, `201` yaradıldı, `204` cavab body-si yoxdur, `401` autentifikasiya tələb olunur, `404` tapılmadı, `422` validasiya xətası. JSON strukturlaşdırılmış məlumat mübadiləsi formatıdır.

**Layihədən nümunə:** `POST /v1/tasks` sorğusunda JSON body ilə `title` və `description` göndərilir; `GET /v1/tasks` isə JSON formatında `data` və `meta.pagination` qaytarır. Qorunan sorğularda `Authorization: Bearer ...` header-i istifadə olunur.

Mənbələr: [MDN HTTP overview](https://developer.mozilla.org/en-US/docs/Web/HTTP/Overview), [MDN HTTP request methods](https://developer.mozilla.org/en-US/docs/Web/HTTP/Methods), [MDN HTTP response status codes](https://developer.mozilla.org/en-US/docs/Web/HTTP/Status), [JSON](https://www.json.org/json-en.html).

### 2. Apiato və Porto arxitekturası

Porto yanaşmasında tətbiq funksionallığı Container-lərə bölünür. Hər Container öz sahəsinə aid kodu saxlayır. Request daxil olan məlumatı yoxlayır, Controller HTTP qatını idarə edir, Action istifadə ssenarisini başladır, Task isə konkret işi görür. Model verilənlər bazası obyektini, Transformer isə API cavabının formasını təmsil edir. Bu bölgü kodu məsuliyyətlərə görə ayırır və dəyişiklikləri lokallaşdırır.

**Layihədən nümunə:** `TaskManagement` Container-də `CreateTaskManagementRequest` daxil olan `title` sahəsini yoxlayır; Controller uyğun Action-u çağırır; Action Task-ı işə salır; Task tapşırığı istifadəçiyə bağlayaraq yaradır; `TaskManagementTransformer` cavabı formatlayır.

Mənbələr: [Apiato — Main Components](https://apiato.io/docs/components/main-components/)

### 3. WEB və API autentifikasiyası

Session autentifikasiyasında server istifadəçi vəziyyətini saxlayır, brauzer isə adətən session cookie göndərir. Bearer token autentifikasiyasında client hər qorunan API sorğusuna `Authorization: Bearer <token>` əlavə edir. `auth:web` WEB guard vasitəsilə sessiyanı yoxlayır; `auth:api` API guard vasitəsilə tokeni yoxlayır. Autentifikasiya istifadəçinin kim olduğunu müəyyən edir; authorization isə həmin istifadəçinin hansı resursa və əməliyyata icazəsi olduğunu yoxlayır.

**Layihədən nümunə:** `/tasks` WEB səhifəsi `auth:web` ilə qorunur. `/v1/tasks` isə `auth:api` tələb edir. TaskManagement query-ləri `user_id` ilə cari istifadəçinin tapşırıqlarını məhdudlaşdırır.

Mənbələr: [Laravel Authentication](https://laravel.com/docs/12.x/authentication), [Laravel Passport](https://laravel.com/docs/12.x/passport).

### 4. JavaScript: Fetch, Promise, async/await və JSON

`fetch()` HTTP sorğusu göndərir və Promise qaytarır. Promise gələcəkdə tamamlanacaq əməliyyatın nəticəsini təmsil edir. `async` funksiyası Promise qaytarır, `await` isə onun nəticəsini gözləyir. JSON serverlə obyekt və massivləri ötürmək üçün istifadə olunur. `JSON.stringify()` JavaScript obyektini JSON mətninə, `response.json()` cavab body-sini JavaScript obyektinə çevirir. `fetch()` HTTP xətalarında da response qaytara bildiyi üçün `response.ok` yoxlanmalıdır.

**Layihədən nümunə:** Task Manager səhifəsi yüklənəndə Fetch ilə `/v1/tasks` çağırır; yaratma və redaktə zamanı JSON body göndərir. Access token etibarsız olduqda WEB refresh endpoint-i ilə yeniləmə cəhdi edilir.

Mənbələr: [MDN — Using Fetch](https://developer.mozilla.org/en-US/docs/Web/API/Fetch_API/Using_Fetch), [MDN — Promise](https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Promise), [MDN — async function](https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Statements/async_function).

### 5. OAuth və Passport

Authentication istifadəçinin kimliyini təsdiqləyir; authorization isə onun hansı resurslara çıxış edə biləcəyini müəyyən edir. OAuth client tətbiqi təmsil edir. Client Secret confidential client-in server tərəfində saxlanılan sirridir və brauzerə göndərilməməlidir. Access token qorunan API-yə müraciət etmək üçündür və qısa müddətlidir. Refresh token istifadəçini yenidən login etdirmədən yeni token almağa imkan verir. Password Grant istifadəçinin login/parol məlumatları ilə token cütü alma axınıdır; bu layihədə Passport Password Grant aktiv edilib və WEB server tərəfində istifadə olunur.

**Layihədən nümunə:** WEB `LoginAction` Passport vasitəsilə access və refresh token alır. Hər ikisi server-side session-da saxlanılır; JavaScript-ə yalnız access token verilir. WEB logout zamanı session-a bağlı access və refresh token ləğv edilir.

Mənbələr: [Laravel Passport 13.x](https://laravel.com/docs/13.x/passport), [OAuth 2.0 — RFC 6749](https://www.rfc-editor.org/info/rfc6749/)..

### 6. Database əlaqələri

Primary key cədvəldə hər sətri unikal müəyyən edir. Foreign key başqa cədvəlin açarına istinad edərək cədvəllər arasında əlaqə yaradır. Eloquent `hasMany` bir modelin bir neçə əlaqəli modelə sahib olduğunu, `belongsTo` isə modelin başqa modelə aid olduğunu bildirir.

**Layihədən nümunə:** `tasks.id` primary key-dir. `tasks.user_id` foreign key olaraq `users.id`-yə bağlanır. `User::tasks()` `hasMany` əlaqəsidir; `TaskManagement::user()` isə `belongsTo` əlaqəsidir. Bir istifadəçinin bir neçə tapşırığı ola bilər, hər tapşırıq bir istifadəçiyə aiddir.

Mənbələr: [Laravel Eloquent Relationships](https://laravel.com/docs/12.x/eloquent-relationships), [Laravel Migrations](https://laravel.com/docs/12.x/migrations).

### 7. CORS və Same-Origin Policy

Same-Origin Policy brauzerin fərqli origin-lər arasında skriptlərin resurslara sərbəst çıxışını məhdudlaşdırır. Origin protokol, host və portdan ibarətdir. CORS serverin hansı xarici origin-lərə brauzer vasitəsilə resurs paylaşmağa icazə verdiyini HTTP header-ləri ilə bildirir. Bəzi sorğulardan əvvəl brauzer `OPTIONS` preflight sorğusu göndərir.

**Layihədən nümunə:** WEB `http://apiato.test`, API isə `http://api.apiato.test` ünvanındadır. Hostlar fərqli olduğundan origin-lər də fərqlidir. API CORS konfiqurasiyası WEB origin-ə icazə verir və `Authorization` header-i ilə gələn Fetch sorğularını qəbul etməyə imkan yaradır.

Mənbələr: [MDN CORS](https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS), [MDN Same-origin policy](https://developer.mozilla.org/en-US/docs/Web/Security/Same-origin_policy), [Laravel CSRF Protection](https://laravel.com/docs/12.x/csrf).
