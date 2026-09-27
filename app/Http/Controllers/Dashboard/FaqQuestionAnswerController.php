<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\FaqQuestionAnswer;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FaqQuestionAnswerController extends Controller
{
    /**
     * Display a listing of FAQs and Questions.
     */
    public function index(Request $request): View
    {
        $query = FaqQuestionAnswer::with(['product', 'category', 'subcategory', 'brand', 'user']);

        // Type Filter (faq / question)
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Status Filter
        if ($request->filled('status')) {
            if ($request->status === 'pending') {
                $query->where('type', 'question')->whereNull('answer');
            } elseif ($request->status === 'answered') {
                $query->where('type', 'question')->whereNotNull('answer');
            } elseif ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Target Entity Filter
        if ($request->filled('target')) {
            switch ($request->target) {
                case 'product':
                    $query->whereNotNull('product_id');
                    break;
                case 'category':
                    $query->whereNotNull('category_id')->whereNull('subcategory_id');
                    break;
                case 'subcategory':
                    $query->whereNotNull('subcategory_id');
                    break;
                case 'brand':
                    $query->whereNotNull('brand_id');
                    break;
                case 'page':
                    $query->whereNotNull('page_name');
                    break;
            }
        }

        // Search Filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('question', 'like', "%{$search}%")
                  ->orWhere('answer', 'like', "%{$search}%")
                  ->orWhere('user_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Trashed filter
        if ($request->get('trashed') === 'only') {
            $query->onlyTrashed();
        }

        $items = $query->orderBy('sort_order')->orderByDesc('id')->paginate(15)->withQueryString();

        // Count Summary Badges
        $counts = [
            'all' => FaqQuestionAnswer::count(),
            'faq' => FaqQuestionAnswer::faq()->count(),
            'question' => FaqQuestionAnswer::question()->count(),
            'pending' => FaqQuestionAnswer::question()->whereNull('answer')->count(),
        ];

        return view('dashboard.faq_qna.index', compact('items', 'counts'));
    }

    /**
     * Show the form for creating a new FAQ / Question.
     */
    public function create(): View
    {
        $products = Product::where('isactive', 1)->orderBy('name')->get(['id', 'name']);
        $categories = Category::whereNull('parent_id')->orderBy('name')->get(['id', 'name']);
        $subcategories = Category::whereNotNull('parent_id')->orderBy('name')->get(['id', 'name', 'parent_id']);
        $brands = Brand::where('status', 1)->orderBy('name')->get(['id', 'name']);

        $pageOptions = [
            'home' => 'Homepage',
            'about' => 'About Us Page',
            'contact' => 'Contact Us Page',
            'privacy' => 'Privacy Policy Page',
            'terms' => 'Terms of Service Page',
            'brandlist' => 'Shop By Brand Page',
            'shop-by-health-concerns' => 'Shop By Health Concerns Page',
        ];

        return view('dashboard.faq_qna.create', compact('products', 'categories', 'subcategories', 'brands', 'pageOptions'));
    }

    /**
     * Store a newly created FAQ / Question in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'type' => 'required|in:faq,question',
            'target_type' => 'nullable|string',
            'product_id' => 'nullable|exists:products,id',
            'category_id' => 'nullable|exists:categories,id',
            'subcategory_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'page_name' => 'nullable|string|max:100',
            'user_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'is_active' => 'nullable|boolean',

            // Multiple FAQs batch support
            'faqs' => 'nullable|array',
            'faqs.*.question' => 'nullable|string|max:2000',
            'faqs.*.answer' => 'nullable|string|max:5000',
            'faqs.*.sort_order' => 'nullable|integer|min:0',

            // Single question fallback
            'question' => 'nullable|string|max:2000',
            'answer' => 'nullable|string|max:5000',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $isActive = $request->has('is_active');
        $createdCount = 0;

        // Process batch FAQs array if present
        if (!empty($request->faqs) && is_array($request->faqs)) {
            foreach ($request->faqs as $faqData) {
                if (empty(trim($faqData['question'] ?? ''))) {
                    continue;
                }

                FaqQuestionAnswer::create([
                    'type' => $validated['type'],
                    'product_id' => $validated['product_id'] ?? null,
                    'category_id' => $validated['category_id'] ?? null,
                    'subcategory_id' => $validated['subcategory_id'] ?? null,
                    'brand_id' => $validated['brand_id'] ?? null,
                    'page_name' => $validated['page_name'] ?? null,
                    'user_name' => $validated['user_name'] ?? null,
                    'email' => $validated['email'] ?? null,
                    'question' => $faqData['question'],
                    'answer' => $faqData['answer'] ?? null,
                    'sort_order' => $faqData['sort_order'] ?? 0,
                    'is_active' => $isActive,
                ]);
                $createdCount++;
            }
        } elseif (!empty($request->question)) {
            // Single question submission fallback
            FaqQuestionAnswer::create([
                'type' => $validated['type'],
                'product_id' => $validated['product_id'] ?? null,
                'category_id' => $validated['category_id'] ?? null,
                'subcategory_id' => $validated['subcategory_id'] ?? null,
                'brand_id' => $validated['brand_id'] ?? null,
                'page_name' => $validated['page_name'] ?? null,
                'user_name' => $validated['user_name'] ?? null,
                'email' => $validated['email'] ?? null,
                'question' => $request->question,
                'answer' => $request->answer ?? null,
                'sort_order' => $request->sort_order ?? 0,
                'is_active' => $isActive,
            ]);
            $createdCount++;
        }

        if ($createdCount === 0) {
            return redirect()->back()->withInput()->with('error', 'Please enter at least one valid question.');
        }

        $msg = $createdCount > 1
            ? "{$createdCount} FAQ / Question items created successfully."
            : "FAQ / Question item created successfully.";

        if ($request->has('redirect_back')) {
            return redirect()->back()->with('success', $msg);
        }

        return redirect()->route('dashboard.faq-qna.index')->with('success', $msg);
    }

    /**
     * Show the form for editing the specified FAQ / Question.
     */
    public function edit(int $id): View
    {
        $faqQna = FaqQuestionAnswer::withTrashed()->findOrFail($id);

        $products = Product::where('isactive', 1)->orderBy('name')->get(['id', 'name']);
        $categories = Category::whereNull('parent_id')->orderBy('name')->get(['id', 'name']);
        $subcategories = Category::whereNotNull('parent_id')->orderBy('name')->get(['id', 'name', 'parent_id']);
        $brands = Brand::where('status', 1)->orderBy('name')->get(['id', 'name']);

        $pageOptions = [
            'home' => 'Homepage',
            'about' => 'About Us Page',
            'contact' => 'Contact Us Page',
            'privacy' => 'Privacy Policy Page',
            'terms' => 'Terms of Service Page',
            'brandlist' => 'Shop By Brand Page',
            'shop-by-health-concerns' => 'Shop By Health Concerns Page',
        ];

        return view('dashboard.faq_qna.edit', compact('faqQna', 'products', 'categories', 'subcategories', 'brands', 'pageOptions'));
    }

    /**
     * Update the specified FAQ / Question in storage.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $faqQna = FaqQuestionAnswer::withTrashed()->findOrFail($id);

        $validated = $request->validate([
            'type' => 'required|in:faq,question',
            'product_id' => 'nullable|exists:products,id',
            'category_id' => 'nullable|exists:categories,id',
            'subcategory_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'page_name' => 'nullable|string|max:100',
            'user_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'question' => 'required|string|max:2000',
            'answer' => 'nullable|string|max:5000',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        $faqQna->update($validated);

        return redirect()->route('dashboard.faq-qna.index')
            ->with('success', 'FAQ / Question updated successfully.');
    }

    /**
     * Quick action for admin to reply to a visitor's question.
     */
    public function reply(Request $request, int $id): RedirectResponse
    {
        $faqQna = FaqQuestionAnswer::findOrFail($id);

        $request->validate([
            'answer' => 'required|string|max:5000',
        ]);

        $faqQna->update([
            'answer' => $request->answer,
            'is_active' => true, // Automatically publish once answered
        ]);

        return redirect()->back()
            ->with('success', 'Answer published successfully for question #' . $faqQna->id);
    }

    /**
     * Remove the specified FAQ / Question (soft delete).
     */
    public function destroy(int $id): RedirectResponse
    {
        $faqQna = FaqQuestionAnswer::findOrFail($id);
        $faqQna->delete();

        return redirect()->route('dashboard.faq-qna.index')
            ->with('success', 'FAQ / Question deleted successfully.');
    }

    /**
     * Restore a soft-deleted FAQ / Question.
     */
    public function restore(int $id): RedirectResponse
    {
        $faqQna = FaqQuestionAnswer::onlyTrashed()->findOrFail($id);
        $faqQna->restore();

        return redirect()->route('dashboard.faq-qna.index')
            ->with('success', 'FAQ / Question restored successfully.');
    }
}
