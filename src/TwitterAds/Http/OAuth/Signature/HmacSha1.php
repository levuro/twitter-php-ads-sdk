<?php

namespace Hborras\TwitterAdsSDK\TwitterAds\Http\OAuth\Signature;

use Hborras\TwitterAdsSDK\TwitterAds\Consumer;
use Hborras\TwitterAdsSDK\TwitterAds\Http\RequestInterface;
use Hborras\TwitterAdsSDK\TwitterAds\Http\Util;
use Hborras\TwitterAdsSDK\TwitterAds\Token;

/**
 * The HMAC-SHA1 signature method uses the HMAC-SHA1 signature algorithm as defined in [RFC2104]
 * where the Signature Base String is the text and the key is the concatenated values (each first
 * encoded per Parameter Encoding) of the Consumer Secret and Token Secret, separated by an '&'
 * character (ASCII code 38) even if empty.
 *   - Chapter 9.2 ("HMAC-SHA1").
 */
class HmacSha1 extends SignatureMethod
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'HMAC-SHA1';
    }

    /**
     * {@inheritdoc}
     */
    public function buildSignature(RequestInterface $request, $auth, Consumer $consumer, Token $token = null)
    {
        $signatureBase = $request->getSignatureBaseString();

        $parts = [$consumer->secret, null !== $token ? $token->secret : ''];

        $parts = Util::urlencodeRfc3986($parts);
        $key = implode('&', $parts);

        return base64_encode(hash_hmac('sha1', $signatureBase, $key, true));
    }
}
