# Research Articles Management Module - Complete Documentation

## 📋 Overview

यह document Research Articles Management Module की complete implementation को explain करता है। यह module Laravel framework पर बना है और एक complete content management system है जो research articles को manage करने के लिए बनाया गया है।

## 🎯 Module Features

### 1. Role-Based Access Control
- **Author**: अपने articles create/edit कर सकता है, लेकिन approve/publish नहीं कर सकता
- **Reviewer**: Articles को approve/reject कर सकता है, लेकिन publish नहीं कर सकता
- **Super Admin**: सभी permissions के साथ articles को publish भी कर सकता है

### 2. Approval Workflow
```
Draft → Submitted → Approved → Published
         ↓
      Rejected
```

### 3. Public Visibility Rules
- Article `published` status में होना चाहिए
- `is_restricted` false होना चाहिए
- `published_at` current time से कम या equal होना चाहिए

### 4. Restricted Articles
- Restricted articles public users को कभी नहीं दिखते
- केवल Admin users को "Restricted" badge के साथ दिखते हैं

## 📁 File Structure

### Database Migrations

```
database/migrations/
├── 2026_01_24_094856_update_users_role_enum_to_include_research_roles.php
├── 2026_01_24_094857_create_research_categories_table.php
├── 2026_01_24_094857_create_research_tags_table.php
├── 2026_01_24_094858_create_research_articles_table.php
├── 2026_01_24_094859_create_research_article_tag_table.php
├── 2026_01_24_094859_create_research_article_media_table.php
└── 2026_01_24_094900_create_research_article_meta_table.php
```

### Models

```
app/Models/
├── ResearchArticle.php
├── ResearchCategory.php
├── ResearchTag.php
├── ResearchArticleMedia.php
├── ResearchArticleMeta.php
└── User.php (updated with role methods)
```

### Controllers

```
app/Http/Controllers/
├── Admin/
│   └── ResearchArticleController.php
└── Public/
    └── ResearchArticleController.php
```

### Middleware

```
app/Http/Middleware/
├── EnsureAuthor.php
├── EnsureReviewer.php
└── EnsureSuperAdmin.php
```

### Views

```
resources/views/
├── admin/research-articles/
│   ├── index.blade.php
│   ├── create.blade.php
│   ├── edit.blade.php
│   ├── show.blade.php
│   └── approval-queue.blade.php
├── public/research-articles/
│   ├── index.blade.php
│   └── show.blade.php
└── components/
    └── research-articles.blade.php
```

### Component

```
app/View/Components/
└── ResearchArticles.php
```

## 🗄️ Database Schema

### research_articles Table

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| title | string | Article title |
| slug | string | URL-friendly slug (unique) |
| excerpt | text | Short description (max 500 chars) |
| content | longtext | Full article content |
| featured_image | string | Featured image path |
| category_id | bigint | Foreign key to research_categories |
| author_id | bigint | Foreign key to users |
| reviewed_by | bigint | Foreign key to users (nullable) |
| published_by | bigint | Foreign key to users (nullable) |
| status | enum | draft, submitted, approved, rejected, published |
| is_restricted | boolean | Restricted access flag |
| published_at | timestamp | Publication date/time |
| reviewed_at | timestamp | Review date/time |
| rejection_reason | text | Reason for rejection |
| views_count | integer | View counter |
| sort_order | integer | Display order |
| created_at | timestamp | Creation timestamp |
| updated_at | timestamp | Update timestamp |
| deleted_at | timestamp | Soft delete timestamp |

### research_categories Table

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| name | string | Category name |
| slug | string | URL-friendly slug (unique) |
| description | text | Category description |
| parent_id | bigint | Parent category (nullable) |
| sort_order | integer | Display order |
| is_active | boolean | Active status |
| created_at | timestamp | Creation timestamp |
| updated_at | timestamp | Update timestamp |

### research_tags Table

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| name | string | Tag name |
| slug | string | URL-friendly slug (unique) |
| description | text | Tag description |
| created_at | timestamp | Creation timestamp |
| updated_at | timestamp | Update timestamp |

### research_article_tag Table (Pivot)

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| research_article_id | bigint | Foreign key to research_articles |
| research_tag_id | bigint | Foreign key to research_tags |
| created_at | timestamp | Creation timestamp |
| updated_at | timestamp | Update timestamp |

### research_article_media Table

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| research_article_id | bigint | Foreign key to research_articles |
| file_path | string | Media file path |
| file_name | string | Original file name |
| file_type | string | File type |
| file_size | integer | File size in bytes |
| mime_type | string | MIME type |
| media_type | enum | image, document, video, other |
| sort_order | integer | Display order |
| alt_text | text | Alt text for images |
| caption | text | Media caption |
| created_at | timestamp | Creation timestamp |
| updated_at | timestamp | Update timestamp |

### research_article_meta Table

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| research_article_id | bigint | Foreign key to research_articles |
| meta_key | string | Meta key |
| meta_value | text | Meta value |
| created_at | timestamp | Creation timestamp |
| updated_at | timestamp | Update timestamp |

## 🔐 Role-Based Access Control

### User Roles

1. **author**
   - अपने articles create/edit कर सकता है
   - Articles को submit कर सकता है
   - Approve/publish नहीं कर सकता

2. **reviewer**
   - सभी submitted articles देख सकता है
   - Articles को approve/reject कर सकता है
   - Publish नहीं कर सकता

3. **super_admin**
   - सभी permissions
   - Approved articles को publish कर सकता है
   - सभी articles manage कर सकता है

### Middleware Implementation

```php
// app/Http/Middleware/EnsureAuthor.php
- Checks if user role is 'author'

// app/Http/Middleware/EnsureReviewer.php
- Checks if user can approve (reviewer or super_admin)

// app/Http/Middleware/EnsureSuperAdmin.php
- Checks if user role is 'super_admin'
```

## 🛣️ Routes

### Public Routes

```php
// Research Articles Listing
GET /public/research/articles
Route: public.research.articles.index

// Research Article Detail
GET /public/research/articles/{slug}
Route: public.research.articles.show
```

### Admin Routes

```php
// Research Articles CRUD
GET    /admin/research-articles              - Index
GET    /admin/research-articles/create       - Create Form
POST   /admin/research-articles              - Store
GET    /admin/research-articles/{id}         - Show
GET    /admin/research-articles/{id}/edit    - Edit Form
PUT    /admin/research-articles/{id}         - Update
DELETE /admin/research-articles/{id}         - Delete

// Approval Queue
GET    /admin/research-articles/approval/queue - Approval Queue

// Approval Actions
POST   /admin/research-articles/{id}/approve   - Approve Article
POST   /admin/research-articles/{id}/reject     - Reject Article
POST   /admin/research-articles/{id}/publish    - Publish Article
```

## 🎨 Frontend Component

### Reusable Component: `<x-research-articles />`

यह component किसी भी page में insert किया जा सकता है बिना additional logic के।

#### Usage Examples

```blade
{{-- Basic Usage --}}
<x-research-articles />

{{-- With Custom Limit --}}
<x-research-articles limit="6" />

{{-- With Category Filter --}}
<x-research-articles limit="6" category="1" />

{{-- With Custom Title --}}
<x-research-articles limit="6" title="Latest Research" />

{{-- Hide View All Button --}}
<x-research-articles limit="6" :showViewAll="false" />
```

#### Component Parameters

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| limit | integer | 6 | Number of articles to display |
| category | integer/null | null | Category ID to filter |
| title | string | 'Research Articles' | Section title |
| showViewAll | boolean | true | Show "View All" button |

#### Component Features

- Automatically fetches only public articles
- Respects `published_at` date
- Filters out restricted articles
- Includes category, author, and tags
- Responsive grid layout
- SEO-friendly structure

## 📝 Controller Methods

### Admin\ResearchArticleController

#### index()
- Lists all articles with filters
- Authors see only their own articles
- Supports filtering by status, category, restricted flag
- Search functionality

#### create()
- Shows create form
- Loads categories and tags

#### store()
- Validates input
- Creates new article
- Handles featured image upload
- Attaches tags
- Sets author automatically

#### show()
- Displays article details
- Shows approval/rejection actions for reviewers
- Shows publish button for super admins

#### edit()
- Shows edit form
- Prevents editing published articles
- Authors can only edit their own articles

#### update()
- Updates article
- Handles image replacement
- Syncs tags
- Validates permissions

#### destroy()
- Soft deletes article
- Deletes associated images
- Validates permissions

#### approvalQueue()
- Lists all submitted articles
- For reviewers and super admins
- Search functionality

#### approve()
- Changes status to 'approved'
- Sets reviewer and reviewed_at
- Clears rejection reason

#### reject()
- Changes status to 'rejected'
- Requires rejection reason
- Sets reviewer and reviewed_at

#### publish()
- Changes status to 'published'
- Sets published_by and published_at
- Only super admin can publish
- Requires article to be approved first

### Public\ResearchArticleController

#### index()
- Lists public articles only
- Filters by category and search
- Pagination support

#### show()
- Displays article detail
- Increments view count
- Shows related articles
- Full content display

## 🎯 Model Relationships

### ResearchArticle

```php
// Belongs To
- category (ResearchCategory)
- author (User)
- reviewer (User)
- publisher (User)

// Belongs To Many
- tags (ResearchTag)

// Has Many
- media (ResearchArticleMedia)
- meta (ResearchArticleMeta)
```

### ResearchCategory

```php
// Belongs To
- parent (ResearchCategory)

// Has Many
- children (ResearchCategory)
- articles (ResearchArticle)
```

### ResearchTag

```php
// Belongs To Many
- articles (ResearchArticle)
```

## 🔍 Scopes & Methods

### ResearchArticle Scopes

```php
// Published articles
ResearchArticle::published()->get();

// Public articles (published + not restricted + published_at <= now)
ResearchArticle::public()->get();

// Pending approval
ResearchArticle::pendingApproval()->get();
```

### ResearchArticle Methods

```php
// Check if published
$article->isPublished();

// Check if public
$article->isPublic();

// Increment views
$article->incrementViews();
```

### User Methods

```php
// Role checks
$user->isAuthor();
$user->isReviewer();
$user->isSuperAdmin();

// Permission checks
$user->canApprove();
$user->canPublish();
```

## 🎨 Admin Views

### Index View (`admin/research-articles/index.blade.php`)

Features:
- Filterable table (status, category, restricted)
- Search functionality
- Status badges with colors
- Restricted badge indicator
- Action buttons (view, edit, delete)
- Pagination
- Role-based button visibility

### Create View (`admin/research-articles/create.blade.php`)

Features:
- Title input
- Excerpt textarea (max 500 chars)
- Content textarea
- Category dropdown
- Featured image upload
- Tags multi-select
- Status dropdown (draft/submitted)
- Restricted checkbox

### Edit View (`admin/research-articles/edit.blade.php`)

Features:
- Same as create view
- Pre-filled with existing data
- Shows current featured image
- Pre-selected tags

### Show View (`admin/research-articles/show.blade.php`)

Features:
- Full article display
- Status badge
- Restricted indicator
- Category, author, dates
- Tags display
- Approval/rejection form (for reviewers)
- Publish button (for super admins)

### Approval Queue View (`admin/research-articles/approval-queue.blade.php`)

Features:
- List of submitted articles
- Quick approve/reject buttons
- Reject modal with reason input
- Search functionality

## 🌐 Public Views

### Index View (`public/research-articles/index.blade.php`)

Features:
- Category filter
- Search functionality
- Responsive grid layout
- Article cards with:
  - Featured image
  - Category badge
  - Title
  - Excerpt
  - Published date
  - Read more link
- Pagination

### Show View (`public/research-articles/show.blade.php`)

Features:
- Full article display
- Featured image
- Category badge
- Author and date info
- Tags display
- View count
- Related articles section
- Back to list link

## 🧩 Component Implementation

### Component Class (`app/View/Components/ResearchArticles.php`)

```php
class ResearchArticles extends Component
{
    public $articles;
    public $limit;
    public $categoryId;
    public $title;
    public $showViewAll;

    public function __construct($limit = 6, $category = null, $title = 'Research Articles', $showViewAll = true)
    {
        // Fetch public articles
        $query = ResearchArticle::public()
            ->with(['category', 'author', 'tags'])
            ->orderBy('published_at', 'desc');

        if ($category) {
            $query->where('category_id', $category);
        }

        $this->articles = $query->limit($limit)->get();
    }
}
```

### Component View (`resources/views/components/research-articles.blade.php`)

- Responsive grid layout
- Article cards
- Category badges
- View All button
- Empty state handling

## 📊 Workflow Examples

### Author Workflow

1. Author creates article → Status: `draft`
2. Author submits article → Status: `submitted`
3. Reviewer reviews → Status: `approved` or `rejected`
4. If rejected, author can edit and resubmit
5. If approved, Super Admin publishes → Status: `published`

### Reviewer Workflow

1. Access Approval Queue
2. View submitted articles
3. Approve or Reject with reason
4. Approved articles appear in publish queue

### Super Admin Workflow

1. Can do everything Author and Reviewer can do
2. Additionally can publish approved articles
3. Can set `published_at` date
4. Full access to all articles

## 🔒 Security Features

1. **Role-Based Access**: Middleware ensures proper permissions
2. **Author Restrictions**: Authors can only edit their own articles
3. **Status Validation**: Cannot edit published articles
4. **File Upload Validation**: Image type and size validation
5. **CSRF Protection**: All forms protected
6. **Soft Deletes**: Articles are soft deleted, not permanently removed

## 📱 Responsive Design

- Mobile-first approach
- Responsive grid layouts
- Touch-friendly buttons
- Optimized images
- Readable typography

## 🚀 Usage Instructions

### 1. Run Migrations

```bash
php artisan migrate
```

### 2. Create Users with Roles

```php
User::create([
    'name' => 'Author Name',
    'email' => 'author@example.com',
    'password' => Hash::make('password'),
    'role' => 'author',
]);

User::create([
    'name' => 'Reviewer Name',
    'email' => 'reviewer@example.com',
    'password' => Hash::make('password'),
    'role' => 'reviewer',
]);

User::create([
    'name' => 'Super Admin',
    'email' => 'admin@example.com',
    'password' => Hash::make('password'),
    'role' => 'super_admin',
]);
```

### 3. Create Categories

```php
ResearchCategory::create([
    'name' => 'Market Analysis',
    'slug' => 'market-analysis',
    'description' => 'Market analysis articles',
    'is_active' => true,
]);
```

### 4. Create Tags

```php
ResearchTag::create([
    'name' => 'Credit Rating',
    'slug' => 'credit-rating',
]);
```

### 5. Use Component in Views

```blade
{{-- In home.blade.php or any other view --}}
<x-research-articles limit="6" title="Latest Research Articles" />
```

## 🐛 Troubleshooting

### Common Issues

1. **Articles not showing publicly**
   - Check: `status` = 'published'
   - Check: `is_restricted` = false
   - Check: `published_at` <= now()

2. **Permission denied errors**
   - Verify user role in database
   - Check middleware registration
   - Ensure routes have correct middleware

3. **Images not displaying**
   - Run: `php artisan storage:link`
   - Check file permissions
   - Verify image path in storage

## 📈 Future Enhancements

Possible improvements:
- Rich text editor for content
- Image gallery support
- PDF export functionality
- Email notifications
- Comment system
- Social sharing
- Analytics integration
- SEO optimization tools

## 📞 Support

For issues or questions:
1. Check this documentation
2. Review code comments
3. Check Laravel logs: `storage/logs/laravel.log`

---

**Module Version**: 1.0.0  
**Last Updated**: January 2026  
**Framework**: Laravel (Latest Stable)  
**License**: Proprietary

