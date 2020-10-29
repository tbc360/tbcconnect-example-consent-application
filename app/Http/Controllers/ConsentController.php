<?php

namespace App\Http\Controllers;

use App\Gateway\HttpRequestGateway;
use Illuminate\Http\Request;

class ConsentController extends Controller
{
    /**
     * Consent Page
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function index()
    {
        $response = HttpRequestGateway::getTermsAndConditions();

        return view('page.consent', ['text' => $response->data->terms_and_conditions]);
    }

    /**
     * CHeck Consent
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function checkConsent(Request $request)
    {
        return response()->json(HttpRequestGateway::checkCustomer($request->user()->email));
    }

    /**
     * Agree On Terms And Conditions
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function agree(Request $request)
    {
        $request->user()->update([
            'tbcconnect_consent' => $request->input('agree')
        ]);

        return response()->json(HttpRequestGateway::agree($request->user()->email, $request->input('agree')));
    }

    /**
     * Check Customer Status
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function checkCustomerStatus(Request $request)
    {
        return response()->json(HttpRequestGateway::checkCustomerStatus($request->user()->email));
    }

    /**
     * Get Terms And Conditions
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getTermsAndConditions(Request $request)
    {
        return response()->json(HttpRequestGateway::getTermsAndConditions());
    }
}
