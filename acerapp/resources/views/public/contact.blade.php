@extends('layouts.public')

@section('title', 'Contact ACER Ratings | Gurugram & Mumbai Offices')
@section('meta_description', 'Contact ACER Ratings at our Gurugram and Mumbai offices for corporate credit ratings, bank loan ratings, research enquiries, and regulatory communications.')
@section('meta_keywords', 'Contact ACER Ratings, ACER Gurugram Office, ACER Mumbai Office, Credit Rating Enquiry India')

@section('content')
    <!-- Main Hero Banner -->
    <x-page-hero 
        title="Contact"
        description="We’re here to help. Reach out to us for rating services, research inquiries, or media queries."
    />

    <!-- No Sub Banner for Contact Page -->
    <x-page-sub-banner :show="false" />

    <!-- Contact Section Component -->
    <x-contact-section 
        sectionClass="pt-12 pb-6"
        layout="stacked"
        title="Write To Us"
        subtitle=""
        :offices="[
            [
                'name' => 'Head Office',
                'address' => 'Unit-808, 8th Floor, Tower -B, Signature Tower, South City I, Sector 30, Gurugram, Haryana 122022',
                'phone' => '+91 124 460 7887',
                'email' => 'contact@acerratings.com '
            ],
            [
                'name' => 'Branch Office (Mumbai)',
                'address' => '1513-14, C Wing, One BKC, Bandra Kurla Complex, Mumbai 400051',
                'phone' => '+91 22 6232 3333',
                'email' => 'contact@acerratings.com'
            ]
        ]"
        formTitle="Get in Touch"
        formSubtitle="Fill out the form below and our team will get back to you shortly."
        :formAction="route('public.contact.store')"
        :showContactButton="false"
    />
@endsection
