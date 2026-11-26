@php
    $cookieCfg = \ExtensionsValley\Cms\Helpers\ThemeHelper::getSection('cookie_policy', $currentLang->code ?? null);
    $cookieDays = max(1, (int) ($cookieCfg['cookie_days'] ?? 365));
@endphp

@if(!empty($cookieCfg['enabled']))
<style>
    .lf-cookie-bar {
        position: fixed;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 9999;
        padding: 16px 20px;
        transform: translateY(115%);
        transition: transform 0.35s cubic-bezier(0.22, 1, 0.36, 1);
        pointer-events: none;
    }
    .lf-cookie-bar.lf-cookie-visible {
        transform: translateY(0);
        pointer-events: auto;
    }
    .lf-cookie-card {
        max-width: 1120px;
        margin: 0 auto;
        background: rgba(15, 23, 42, 0.97);
        color: #e2e8f0;
        border: 1px solid rgba(255, 255, 255, 0.12);
        border-radius: 14px;
        padding: 20px 24px;
        box-shadow: 0 20px 50px rgba(2, 6, 23, 0.45);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 24px;
        flex-wrap: wrap;
    }
    .lf-cookie-text {
        flex: 1 1 520px;
        font-size: 0.92rem;
        line-height: 1.55;
    }
    .lf-cookie-text h4 {
        margin: 0 0 6px 0;
        font-size: 1.05rem;
        font-weight: 700;
        color: #ffffff;
    }
    .lf-cookie-text p { margin: 0 0 4px 0; color: #cbd5e1; }
    .lf-cookie-text a { color: #60a5fa; text-decoration: underline; }
    .lf-cookie-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }
    .lf-cookie-btn-reject {
        background: transparent;
        color: #e2e8f0;
        border: 1px solid rgba(255, 255, 255, 0.25);
        padding: 10px 18px;
        border-radius: 8px;
        font-size: 0.9rem;
        font-weight: 600;
        cursor: pointer;
    }
    .lf-cookie-btn-reject:hover { background: rgba(255, 255, 255, 0.08); }
    .lf-cookie-btn-accept {
        background: var(--lf-primary, #2563eb);
        color: #ffffff;
        border: 0;
        padding: 10px 22px;
        border-radius: 8px;
        font-size: 0.9rem;
        font-weight: 600;
        cursor: pointer;
    }
    .lf-cookie-btn-accept:hover { opacity: 0.92; }
</style>

<div id="lfCookieBanner" class="lf-cookie-bar" role="dialog" aria-live="polite" aria-label="Cookie Consent">
    <div class="lf-cookie-card">
        <div class="lf-cookie-text">
            @if(!empty($cookieCfg['title']))
                <h4>{{ $cookieCfg['title'] }}</h4>
            @endif
            <div>{!! $cookieCfg['content'] ?? '' !!}</div>
            @if(!empty($cookieCfg['policy_page_slug']))
                <div style="margin-top:6px;">
                    <a href="{{ \ExtensionsValley\Cms\Helpers\CmsHelper::url('page/' . $cookieCfg['policy_page_slug']) }}">
                        Learn more
                    </a>
                </div>
            @endif
        </div>
        <div class="lf-cookie-actions">
            <button type="button" class="lf-cookie-btn-reject" onclick="window.lafluxSetCookieConsent('rejected')">
                {{ $cookieCfg['reject_label'] ?: 'Reject' }}
            </button>
            <button type="button" class="lf-cookie-btn-accept" onclick="window.lafluxSetCookieConsent('accepted')">
                {{ $cookieCfg['accept_label'] ?: 'Accept All' }}
            </button>
        </div>
    </div>
</div>

<script>
    (function () {
        var COOKIE_NAME = 'laflux_cookie_consent';
        var COOKIE_DAYS = {{ $cookieDays }};

        function readConsent() {
            var match = document.cookie.match(new RegExp('(?:^|; )' + COOKIE_NAME + '=([^;]*)'));
            if (match && match[1]) return decodeURIComponent(match[1]);
            try { return localStorage.getItem(COOKIE_NAME); } catch (e) { return null; }
        }

        window.lafluxSetCookieConsent = function (choice) {
            var maxAge = COOKIE_DAYS * 86400;
            document.cookie = COOKIE_NAME + '=' + encodeURIComponent(choice) + '; path=/; max-age=' + maxAge + '; SameSite=Lax';
            try { localStorage.setItem(COOKIE_NAME, choice); } catch (e) {}

            var el = document.getElementById('lfCookieBanner');
            if (el) el.classList.remove('lf-cookie-visible');

            window.dispatchEvent(new CustomEvent('laflux:cookie-consent', { detail: { status: choice } }));
        };

        if (!readConsent()) {
            setTimeout(function () {
                var el = document.getElementById('lfCookieBanner');
                if (el) el.classList.add('lf-cookie-visible');
            }, 350);
        }
    })();
</script>
@endif

