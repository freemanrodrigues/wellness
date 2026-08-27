@include('layouts.admin-header')

@include('layouts.admin-navbar')

<div class="dash-wrapper">
    <main class="dash-main mt-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="dash-page-title m-0">Edit Blog Post</h1>
            <a href="{{ route('dashboard.blog.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Back to Blog Posts
            </a>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger mb-4">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('dashboard.blog.update', $blog) }}" enctype="multipart/form-data" class="card border-0 shadow-sm p-4 rounded-3 bg-white">
            @csrf
            @method('PUT')

            <div class="row g-3">
                {{-- Title --}}
                <div class="col-md-8">
                    <label class="form-label fw-semibold">Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $blog->title) }}" required>
                </div>

                {{-- URL Slug --}}
                <div class="col-md-4">
                    <label class="form-label fw-semibold">URL Slug <span class="text-danger">*</span></label>
                    <input type="text" name="url" class="form-control" value="{{ old('url', $blog->url) }}" required>
                </div>

                {{-- Category --}}
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Category</label>
                    <select name="cat_id" class="form-select">
                        <option value="">-- Select Category --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('cat_id', $blog->cat_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Sort Order --}}
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Sort Order</label>
                    <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $blog->sort_order) }}">
                </div>

                {{-- Status --}}
                <div class="col-md-3 d-flex align-items-center mt-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="status" id="statusSwitch" value="1" {{ old('status', $blog->status) ? 'checked' : '' }}>
                        <label class="form-check-label fw-medium" for="statusSwitch">Active Status</label>
                    </div>
                </div>

                {{-- Page Show --}}
                <div class="col-md-3 d-flex align-items-center mt-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="page_show" id="pageShowSwitch" value="1" {{ old('page_show', $blog->page_show) ? 'checked' : '' }}>
                        <label class="form-check-label fw-medium" for="pageShowSwitch">Display on Public Page</label>
                    </div>
                </div>

                {{-- Excerpt --}}
                <div class="col-12">
                    <label class="form-label fw-semibold">Excerpt / Short Description</label>
                    <textarea name="excerpt" class="form-control" rows="2">{{ old('excerpt', $blog->excerpt) }}</textarea>
                </div>

                {{-- Image Preview & Upload --}}
                <div class="col-12">
                    @if($blog->image)
                        <div class="mb-2">
                            <label class="form-label fw-semibold d-block">Current Featured Image:</label>
                            <img src="{{ str_starts_with($blog->image, 'http') ? $blog->image : asset($blog->image) }}" alt="Current Image" style="max-height:120px;border-radius:.5rem;" class="border p-1">
                        </div>
                    @endif
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Upload New Image File</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">OR Image URL</label>
                    <input type="text" name="image_url" class="form-control" value="{{ old('image_url', str_starts_with($blog->image ?? '', 'http') ? $blog->image : '') }}" placeholder="https://example.com/image.jpg">
                </div>

                {{-- Tags / Hashtags --}}
                <div class="col-12">
                    <label class="form-label fw-semibold">Hashtags / Tags</label>
                    <input type="text" name="tags" class="form-control" value="{{ old('tags', $blog->tags) }}" placeholder="#wellness #health #nutrition">
                </div>

                {{-- Blog Meta Title & Meta Description (SEO) --}}
                <div class="col-md-12">
                    <label class="form-label fw-semibold">Blog Meta Title</label>
                    <input type="text" name="blog_meta_title" class="form-control" value="{{ old('blog_meta_title', $blog->blog_meta_title) }}" placeholder="SEO Meta Title">
                </div>

                <div class="col-md-12">
                    <label class="form-label fw-semibold">Blog Meta Description</label>
                    <textarea name="blog_meta_description" class="form-control" rows="3" placeholder="SEO Meta Description summarizing the blog content for search engines">{{ old('blog_meta_description', $blog->blog_meta_description) }}</textarea>
                </div>

                {{-- Full Description --}}
                <div class="col-12">
                    <label class="form-label fw-semibold">Full Blog Description (HTML / Images / Videos)</label>
                    <textarea name="description" id="blogDescription" class="form-control" rows="12">{{ old('description', $blog->description) }}</textarea>
                </div>

                {{-- Submit Button --}}
                <div class="col-12 mt-4 text-end">
                    <button type="submit" class="btn btn-primary px-4 fw-bold">
                        <i class="bi bi-check-circle me-1"></i> Update Blog Post
                    </button>
                </div>
            </div>
        </form>

    </main>
</div>

@include('layouts.dash_styles')

{{-- Summernote Editor Styles & Scripts --}}
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script>
    $(document).ready(function() {
        $('#blogDescription').summernote({
            placeholder: 'Enter complete blog post content here...',
            tabsize: 2,
            height: 380,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });
    });
</script>

@include('layouts.admin-footer')
