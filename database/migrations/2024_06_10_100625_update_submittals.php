<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('submittals', function (Blueprint $table) {
            // Drop 'name' column if it exists
            if (Schema::hasColumn('submittals', 'name')) {
                $table->dropColumn('name');
            }

            // Add new columns if they do not already exist
            if (!Schema::hasColumn('submittals', 'transactionName')) {
                $table->string('transactionName')->nullable();
            }
            if (!Schema::hasColumn('submittals', 'additionalEmail')) {
                $table->string('additionalEmail')->nullable();
            }
            if (!Schema::hasColumn('submittals', 'agentName')) {
                $table->string('agentName')->nullable();
            }
            if (!Schema::hasColumn('submittals', 'commingSoon')) {
                $table->string('commingSoon')->nullable();
            }
            if (!Schema::hasColumn('submittals', 'comingSoonDate')) {
                $table->date('comingSoonDate')->nullable();
            }
            if (!Schema::hasColumn('submittals', 'tmName')) {
                $table->string('tmName')->nullable();
            }
            if (!Schema::hasColumn('submittals', 'activeDate')) {
                $table->date('activeDate')->nullable();
            }
            if (!Schema::hasColumn('submittals', 'agreementExecuted')) {
                $table->string('agreementExecuted')->nullable();
            }
            if (!Schema::hasColumn('submittals', 'price')) {
                $table->decimal('price', 10, 2)->nullable();
            }
            if (!Schema::hasColumn('submittals', 'photoDate')) {
                $table->date('photoDate')->nullable();
            }
            if (!Schema::hasColumn('submittals', 'photoURL')) {
                $table->string('photoURL')->nullable();
            }
            if (!Schema::hasColumn('submittals', 'bedsBathsTotal')) {
                $table->string('bedsBathsTotal')->nullable();
            }
            if (!Schema::hasColumn('submittals', 'tourURL')) {
                $table->string('tourURL')->nullable();
            }
            if (!Schema::hasColumn('submittals', 'usingCHR')) {
                $table->string('usingCHR')->nullable();
            }
            if (!Schema::hasColumn('submittals', 'needOE')) {
                $table->string('needOE')->nullable();
            }
            if (!Schema::hasColumn('submittals', 'hasHOA')) {
                $table->string('hasHOA')->nullable();
            }
            if (!Schema::hasColumn('submittals', 'includeInsights')) {
                $table->string('includeInsights')->nullable();
            }
            if (!Schema::hasColumn('submittals', 'titleToOrderHOA')) {
                $table->string('titleToOrderHOA')->nullable();
            }
            if (!Schema::hasColumn('submittals', 'mailoutNeeded')) {
                $table->string('mailoutNeeded')->nullable();
            }
            if (!Schema::hasColumn('submittals', 'powerOfAttnyNeeded')) {
                $table->string('powerOfAttnyNeeded')->nullable();
            }
            if (!Schema::hasColumn('submittals', 'hoaName')) {
                $table->string('hoaName')->nullable();
            }
            if (!Schema::hasColumn('submittals', 'hoaPhone')) {
                $table->string('hoaPhone')->nullable();
            }
            if (!Schema::hasColumn('submittals', 'hoaWebsite')) {
                $table->string('hoaWebsite')->nullable();
            }
            if (!Schema::hasColumn('submittals', 'miscNotes')) {
                $table->text('miscNotes')->nullable();
            }
            if (!Schema::hasColumn('submittals', 'scheduleSignInstall')) {
                $table->string('scheduleSignInstall')->nullable();
            }
            if (!Schema::hasColumn('submittals', 'conciergeListing')) {
                $table->string('conciergeListing')->nullable();
            }
            if (!Schema::hasColumn('submittals', 'signInstallVendor')) {
                $table->string('signInstallVendor')->nullable();
            }
            if (!Schema::hasColumn('submittals', 'draftShowingInstructions')) {
                $table->text('draftShowingInstructions')->nullable();
            }
            if (!Schema::hasColumn('submittals', 'titleCompany')) {
                $table->string('titleCompany')->nullable();
            }
            if (!Schema::hasColumn('submittals', 'closerNamePhone')) {
                $table->string('closerNamePhone')->nullable();
            }
            if (!Schema::hasColumn('submittals', 'signInstallVendorOther')) {
                $table->string('signInstallVendorOther')->nullable();
            }
            if (!Schema::hasColumn('submittals', 'signInstallDate')) {
                $table->date('signInstallDate')->nullable();
            }
            if (!Schema::hasColumn('submittals', 'reColorado')) {
                $table->string('reColorado')->nullable();
            }
            if (!Schema::hasColumn('submittals', 'navica')) {
                $table->string('navica')->nullable();
            }
            if (!Schema::hasColumn('submittals', 'ppar')) {
                $table->string('ppar')->nullable();
            }
            if (!Schema::hasColumn('submittals', 'grandCounty')) {
                $table->string('grandCounty')->nullable();
            }
            if (!Schema::hasColumn('submittals', 'ires')) {
                $table->string('ires')->nullable();
            }
            if (!Schema::hasColumn('submittals', 'mlsPrivateRemarks')) {
                $table->text('mlsPrivateRemarks')->nullable();
            }
            if (!Schema::hasColumn('submittals', 'mlsPublicRemarks')) {
                $table->text('mlsPublicRemarks')->nullable();
            }
            if (!Schema::hasColumn('submittals', 'feesCharged')) {
                $table->string('feesCharged')->nullable();
            }
            if (!Schema::hasColumn('submittals', 'referralToPay')) {
                $table->string('referralToPay')->nullable();
            }
            if (!Schema::hasColumn('submittals', 'amountToCHR')) {
                $table->decimal('amountToCHR', 10, 2)->nullable();
            }
            if (!Schema::hasColumn('submittals', 'referralDetails')) {
                $table->text('referralDetails')->nullable();
            }
            if (!Schema::hasColumn('submittals', 'matterport')) {
                $table->string('matterport')->nullable();
            }
            if (!Schema::hasColumn('submittals', 'floorPlans')) {
                $table->string('floorPlans')->nullable();
            }
            if (!Schema::hasColumn('submittals', 'threeDZillowTour')) {
                $table->string('threeDZillowTour')->nullable();
            }
            if (!Schema::hasColumn('submittals', 'onsiteVideo')) {
                $table->string('onsiteVideo')->nullable();
            }
            if (!Schema::hasColumn('submittals', 'propertyWebsite')) {
                $table->string('propertyWebsite')->nullable();
            }
            if (!Schema::hasColumn('submittals', 'emailBlastSphere')) {
                $table->string('emailBlastSphere')->nullable();
            }
            if (!Schema::hasColumn('submittals', 'emailBlastReverseProspect')) {
                $table->string('emailBlastReverseProspect')->nullable();
            }
            if (!Schema::hasColumn('submittals', 'propertyHighlightVideo')) {
                $table->string('propertyHighlightVideo')->nullable();
            }
            if (!Schema::hasColumn('submittals', 'socialMediaImages')) {
                $table->string('socialMediaImages')->nullable();
            }
            if (!Schema::hasColumn('submittals', 'socialMediaAds')) {
                $table->string('socialMediaAds')->nullable();
            }
            if (!Schema::hasColumn('submittals', 'priceImprovementPackage')) {
                $table->string('priceImprovementPackage')->nullable();
            }
            if (!Schema::hasColumn('submittals', 'customDomainName')) {
                $table->string('customDomainName')->nullable();
            }
            if (!Schema::hasColumn('submittals', 'featuresNeededForVideo')) {
                $table->text('featuresNeededForVideo')->nullable();
            }
            if (!Schema::hasColumn('submittals', 'marketingNotes')) {
                $table->text('marketingNotes')->nullable();
            }
            if (!Schema::hasColumn('submittals', 'brochureLine')) {
                $table->string('brochureLine')->nullable();
            }
            if (!Schema::hasColumn('submittals', 'brochurePrint')) {
                $table->string('brochurePrint')->nullable();
            }
            if (!Schema::hasColumn('submittals', 'bullets')) {
                $table->text('bullets')->nullable();
            }
            if (!Schema::hasColumn('submittals', 'headlineForBrochure')) {
                $table->string('headlineForBrochure')->nullable();
            }
            if (!Schema::hasColumn('submittals', 'stickyDots')) {
                $table->string('stickyDots')->nullable();
            }
            if (!Schema::hasColumn('submittals', 'qrCodeSheet')) {
                $table->string('qrCodeSheet')->nullable();
            }
            if (!Schema::hasColumn('submittals', 'qrCodeSignRider')) {
                $table->string('qrCodeSignRider')->nullable();
            }
            if (!Schema::hasColumn('submittals', 'featureCards')) {
                $table->string('featureCards')->nullable();
            }
            if (!Schema::hasColumn('submittals', 'featureCardCopy')) {
                $table->string('featureCardCopy')->nullable();
            }
            if (!Schema::hasColumn('submittals', 'brochureDeliveryDate')) {
                $table->date('brochureDeliveryDate')->nullable();
            }
            if (!Schema::hasColumn('submittals', 'deliveryAddress')) {
                $table->string('deliveryAddress')->nullable();
            }
            if (!Schema::hasColumn('submittals', 'printedItemsPickupDate')) {
                $table->date('printedItemsPickupDate')->nullable();
            }
            if (!Schema::hasColumn('submittals', 'brochurePickupDate')) {
                $table->date('brochurePickupDate')->nullable();
            }
            if (!Schema::hasColumn('submittals', 'isSubmittalComplete')) {
                $table->string('isSubmittalComplete')->nullable();
            }
            if (!Schema::hasColumn('submittals', 'submittalName')) {
                $table->string('submittalName')->nullable();
            }
            if (!Schema::hasColumn('submittals', 'submittalType')) {
                $table->string('submittalType')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('submittals', function (Blueprint $table) {
            // Drop the added columns
            $table->dropColumn([
                'transactionName',
                'additionalEmail',
                'agentName',
                'commingSoon',
                'comingSoonDate',
                'tmName',
                'activeDate',
                'agreementExecuted',
                'price',
                'photoDate',
                'photoURL',
                'bedsBathsTotal',
                'tourURL',
                'usingCHR',
                'needOE',
                'hasHOA',
                'includeInsights',
                'titleToOrderHOA',
                'mailoutNeeded',
                'powerOfAttnyNeeded',
                'hoaName',
                'hoaPhone',
                'hoaWebsite',
                'miscNotes',
                'scheduleSignInstall',
                'conciergeListing',
                'signInstallVendor',
                'draftShowingInstructions',
                'titleCompany',
                'closerNamePhone',
                'signInstallVendorOther',
                'signInstallDate',
                'reColorado',
                'navica',
                'ppar',
                'grandCounty',
                'ires',
                'mlsPrivateRemarks',
                'mlsPublicRemarks',
                'feesCharged',
                'referralToPay',
                'amountToCHR',
                'referralDetails',
                'matterport',
                'floorPlans',
                'threeDZillowTour',
                'onsiteVideo',
                'propertyWebsite',
                'emailBlastSphere',
                'emailBlastReverseProspect',
                'propertyHighlightVideo',
                'socialMediaImages',
                'socialMediaAds',
                'priceImprovementPackage',
                'customDomainName',
                'featuresNeededForVideo',
                'marketingNotes',
                'brochureLine',
                'brochurePrint',
                'bullets',
                'headlineForBrochure',
                'stickyDots',
                'qrCodeSheet',
                'qrCodeSignRider',
                'featureCards',
                'featureCardCopy',
                'brochureDeliveryDate',
                'deliveryAddress',
                'printedItemsPickupDate',
                'brochurePickupDate',
                'isSubmittalComplete',
                'submittalName',
                'submittalType',
            ]);

            // Restore the 'name' column
            $table->string('name')->nullable();
        });
    }
};
