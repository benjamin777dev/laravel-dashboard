<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Submittals extends Model
{
    use HasFactory;

     protected $fillable = [
        'name',
        "userId",
        "dealId",
        "zoho_submittal_id",
        'submittalName',
        'submittalType',
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
        'formType',
        'qrCodeMainPanel'
    ];

    public static function getZohoContactInfo()
    {
        // Retrieve Zoho contact ID, last name, and first name
        return self::select('zoho_contact_id', 'last_name', 'first_name')->get();
    }

    public function userData()
    {
        return $this->belongsTo(User::class, 'userId');
    }

    public function dealData()
    {
        return $this->belongsTo(Deal::class, 'dealId','zoho_deal_id');
    }
}
