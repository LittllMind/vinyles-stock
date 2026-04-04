<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class NewsletterAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    public function index(): JsonResponse
    {
        $subscribers = NewsletterSubscriber::orderBy('created_at', 'desc')
            ->paginate(50);

        return response()->json($subscribers);
    }

    public function destroy(NewsletterSubscriber $subscriber): JsonResponse
    {
        $subscriber->delete();

        return response()->json([
            'message' => 'Inscrit supprimé.',
        ]);
    }

    public function export(): StreamedResponse
    {
        $subscribers = NewsletterSubscriber::confirmed()
            ->orderBy('confirmed_at')
            ->get(['email', 'confirmed_at', 'created_at']);

        $response = new StreamedResponse(function () use ($subscribers) {
            $handle = fopen('php://output', 'w');
            
            // BOM UTF-8
            fprintf($handle, "\xEF\xBB\xBF");
            
            // Headers
            fputcsv($handle, ['Email', 'Confirmé le', 'Inscrit le']);

            foreach ($subscribers as $subscriber) {
                fputcsv($handle, [
                    $subscriber->email,
                    $subscriber->confirmed_at?->format('d/m/Y H:i:s'),
                    $subscriber->created_at->format('d/m/Y H:i:s'),
                ]);
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv; charset=UTF-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="newsletter-subscribers.csv"');

        return $response;
    }
}
