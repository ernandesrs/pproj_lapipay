<?php

namespace Ernandesrs\Lapipay\Services\Pagarme;

trait Postback
{
    /**
     * Postback
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function postback(\Illuminate\Http\Request $request)
    {
        /**
         * https://docs.pagar.me/v4/reference/postbacks
         */
        $postbackPayload = $request->all();

        if (!$this->pagarme->postbacks()->validate($request->getContent(), $request->header('X-Hub-Signature'))) {
            return;
        }

        switch ($postbackPayload['object']) {
            case 'transaction':
                $this->transactionPostback($postbackPayload['transaction']);
                break;
        }
    }

    /**
     * Transaction postback
     *
     * @param array $transaction
     * @return void
     */
    private function transactionPostback(array $transaction)
    {
        $payment = \Ernandesrs\Lapipay\Models\Payment::where('transaction_id', $transaction['id'])->first();
        if (!$payment) {
            return;
        }

        $payment->status = $transaction['status'];
        $payment->save();
    }

    /**
     * Get postback url
     *
     * @return string
     */
    private function postbackUrl()
    {
        return empty(config('lapipay.postback_url', '')) || !config('lapipay.testing') ?
            route('lapipay.postback') :
            config('lapipay.postback_url');
    }
}