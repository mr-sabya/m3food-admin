<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        // Retrieve all active banners, ordered by the 'order' field
        $banners = Banner::where('is_active', true)
            ->orderBy('order')
            ->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Active banners retrieved successfully',
            'data' => $banners,
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        // Find the banner by ID and ensure it's active
        $banner = Banner::where('id', $id)
            ->where('is_active', true)
            ->first();

        if (!$banner) {
            return response()->json([
                'status' => 'error',
                'message' => 'Banner not found or is not active',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Banner retrieved successfully',
            'data' => $banner,
        ], 200);
    }
}
