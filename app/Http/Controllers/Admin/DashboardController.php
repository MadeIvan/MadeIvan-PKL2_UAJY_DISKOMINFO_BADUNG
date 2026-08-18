<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Category;
use App\Models\TutorialNode;
use App\Models\ApplicationVersion;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        // Overview statistics
        $stats = [
            'total_applications' => Application::count(),
            'active_applications' => Application::where('status', 'active')->count(),
            'total_categories' => Category::count(),
            'total_materi' => TutorialNode::count(),
            'total_versions' => ApplicationVersion::count(),
        ];

        // Recent activity
        $recentApplications = Application::with('category')
            ->latest()
            ->take(5)
            ->get();

        $recentMateri = TutorialNode::with('application')
            ->latest('updated_at')
            ->take(5)
            ->get();

        // Category Distribution (how many apps per category)
        $categoryDistribution = Category::withCount('applications')
            ->having('applications_count', '>', 0)
            ->orderBy('applications_count', 'desc')
            ->take(5)
            ->get();

        return view('Admin.dashboard.index', compact(
            'stats',
            'recentApplications',
            'recentMateri',
            'categoryDistribution'
        ));
    }
}
