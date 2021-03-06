<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Cloud;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CloudController extends Controller {

    /**
     * Handles the cloud save functionality
     *
     * @param Request $request The request object
     * @param User $user The user detected from the bearer token
     * @return JsonResponse
     */
    public function save(Request $request, User $user): JsonResponse {
        $requestData = $request->getContent();

        $requestData = json_decode($requestData, true)['params'];

        $save_name = urldecode($requestData['save_name']);
        $content = base64_encode(gzcompress(urlencode($requestData['saved_content']), 9));

        $cloud_save = Cloud::create([
            'user_id' => $user->id,
            'save_name' => $save_name,
            'saved_content' => $content
        ]);

        $cloud_save->save();

        return new JsonResponse([
            "message" => 'Saved file succcessfully',
            'status' => 200
        ], 200);
    }

    /**
     * Handles the cloud load functionality
     *
     * @param Request $request The request object
     * @param User $user The user detected from the bearer token
     * @return JsonResponse
     */
    public function load(Request $request, User $user): JsonResponse {
        $requestData = $request->getContent();

        $requestData = json_decode($requestData, true)['params'];

        $save_id = $requestData['save_id'];

        $cloud_save = DB::table('cloud')->where('id', $save_id)->where('user_id', $user->id)->first();


        if ($cloud_save != null) {
            return new JsonResponse([
                "message" => 'Saved file succcessfully',
                "file_content" => gzuncompress(base64_decode($cloud_save->saved_content)),
                'status' => 200
            ], 200);
        } else {
            return new JsonResponse([
                "message" => 'File could not be loaded.',
                'status' => 500
            ], 500);
        }
    }

    /**
     * Get list of files user has saved
     *
     * @param Request $request The request object
     * @param User $user The user detected from the bearer token
     * @return JsonResponse
     */
    public function getListOfSavedFiles(Request $request, User $user): JsonResponse {
        $cloud_saves = DB::table('cloud')->where('user_id', $user->id)->select('id', 'save_name', 'created_at', 'updated_at')->get();

        if ($cloud_saves != null) {
            return new JsonResponse([
                "message" => 'Loaded files successfully',
                "file_list" => json_encode($cloud_saves),
                "status" => 200
            ], 200);
        } else {
            return new JsonResponse([
                "message" => 'No saved files found',
                "status" => 400
            ], 400);
        }
    }
}
