<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TransController extends Controller
{
    public function translate(Request $request)
    {
        $text = $request->input('text');

        if (!$text) {
            return response()->json(['error' => 'Text is required'], 400);
        }

        try {
            $response = Http::get('https://translate.googleapis.com/translate_a/single', [
                'client' => 'gtx',
                'sl'     => 'en',
                'tl'     => 'hi',
                'dt'     => 't',
                'q'      => $text,
            ]);

            $translated = $response[0][0][0] ?? null;

            return response()->json([
                'translated' => $translated
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
