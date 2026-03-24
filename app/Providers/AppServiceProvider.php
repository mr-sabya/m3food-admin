<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Relation::morphMap([
            'benefit_section'      => \App\Models\Section\BenefitSection::class,
            'caution_section'      => \App\Models\Section\CautionSection::class,
            'comparison_section'   => \App\Models\Section\ComparisonSection::class,
            'countdown_section'    => \App\Models\Section\CountdownSection::class,
            'disclaimer_section'   => \App\Models\Section\DisclaimerSection::class,
            'feature_card_section' => \App\Models\Section\FeatureCardSection::class,
            'marketing_highlight'  => \App\Models\Section\MarketingHighlightSection::class,
            'media_news_section'   => \App\Models\Section\MediaNewsSection::class,
            'social_proof_section' => \App\Models\Section\SocialProofSection::class,
            'title_section'        => \App\Models\Section\TitleSection::class,
            'usage_section'        => \App\Models\Section\UsageSection::class,
            'variation_price'      => \App\Models\Section\VariationPriceSection::class,
            'video_section'        => \App\Models\Section\VideoSection::class,
            'why_choose'           => \App\Models\Section\WhyChoose::class,
        ]);
    }
}
