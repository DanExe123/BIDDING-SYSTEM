<div class="flex min-h-screen">
    <!-- Sidebar -->
    @include('partials.user-sidebar')

    <!-- Main content area (topbar + page content) -->
    <div class="flex-1 flex flex-col bg-white min-h-screen">
        <!-- Topbar -->
        <header class="bg-white h-16 flex items-center justify-between px-6 shadow">
            <h1 class="text-xl font-semibold">USERS GUIDE</h1>
            <!-- Right Section -->
            <div class="flex items-center gap-4">
                <!-- Search Bar -->
                <div class="relative">
                    <input type="text" placeholder="Search..."
                        class="pl-10 pr-4 py-2 rounded-full border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#062B4A]" />
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 mt-2.5">
                        <x-phosphor.icons::regular.magnifying-glass class="w-5 h-5" />
                    </span>
                </div>
             
        </header>

        <main class="p-6 space-y-6 flex-1" x-data="guideCarousel()" x-init="init()">
             <!-- Intro Image -->
             <div class="flex flex-col items-center justify-center text-center gap-6 py-10">
                <!-- Intro Image -->
                <img src="{{ asset('icon/12083693_Wavy_Bus-33_Single-12.jpg') }}" 
                     alt="User Guide Illustration"
                     class="w-full max-w-xl rounded-2xl">
            
                <!-- Intro Paragraph -->
                @role('Super_Admin')
                <p class="text-gray-700 text-lg max-w-3xl leading-relaxed">
                    Welcome to the <span class="font-semibold text-[#062B4A]">Super Admin User Guide</span>. 
                    This guide is designed to help you understand and navigate the essential features of your system — 
                    including the Dashboard, User Management, and Audit Trails. Take your time to follow each section carefully 
                    and read the provided descriptions to gain a full understanding of how each feature works and how you can 
                    use them efficiently to manage your system operations with ease and confidence.
                </p>
                @endrole
                @role('Purchaser')
                <p class="text-gray-700 text-lg max-w-3xl leading-relaxed">
                    Welcome to the <span class="font-semibold text-[#062B4A]">Purchaser User Guide</span>. 
                    As a Purchaser, you can view important <span class="font-semibold text-[#062B4A]">Announcements</span>, manage 
                    <span class="font-semibold text-[#062B4A]">Procurement Planning</span>, and monitor bids efficiently. 
                    Create and submit your <span class="font-semibold text-[#062B4A]">PPMP</span> by providing project details such as title, type, budget, implementing unit, and procurement items with their descriptions, quantities, and costs. 
                    You can also attach files before submission and review all entries in the <span class="font-semibold text-[#062B4A]">List of PPMP Entries</span> to track their purposes and approval status. 
                    Use the <span class="font-semibold text-[#062B4A]">Bid Monitoring</span> feature to stay updated on procurement progress and ensure transparency in every transaction.
                </p>
            @endrole
            @role('BAC_Secretary')
            <p class="text-gray-700 text-lg max-w-3xl leading-relaxed">
                Welcome to the <span class="font-semibold text-[#062B4A]">BAC Secretary User Guide</span>. 
                As a BAC Secretary, you are responsible for managing and monitoring all procurement activities within the system. 
                This includes reviewing <span class="font-semibold text-[#062B4A]">Purchase Requests</span>, overseeing the <span class="font-semibold text-[#062B4A]">Mode of Procurement</span>, 
                tracking progress through the <span class="font-semibold text-[#062B4A]">Procurement Workflow</span>, and issuing the <span class="font-semibold text-[#062B4A]">Notice of Award</span> when applicable. 
                You can also use the <span class="font-semibold text-[#062B4A]">Dashboard</span> to view ongoing procurements, open bids, and supplier information at a glance. 
                This guide will help you navigate each feature effectively to ensure smooth, transparent, and compliant procurement operations.
            </p>
            @endrole
            @role('Supplier')
            <p class="text-gray-700 text-lg max-w-3xl leading-relaxed">
                Welcome to the <span class="font-semibold text-[#062B4A]">Supplier User Guide</span>. 
                As a Supplier, this guide will help you navigate and manage your participation in procurement activities efficiently. 
                Through the <span class="font-semibold text-[#062B4A]">Dashboard</span>, you can view important updates such as active procurements, recent activities, and announcements. 
                In the <span class="font-semibold text-[#062B4A]">Invitations</span> section, you can review procurement opportunities and respond by submitting your proposals in the 
                <span class="font-semibold text-[#062B4A]">Proposal Submission</span> module. 
                Additionally, the <span class="font-semibold text-[#062B4A]">Notice of Award</span> section allows you to track awarded contracts and monitor the status of your submissions. 
                Use this guide to understand each feature and make the most out of your supplier account to engage effectively in the procurement process.
            </p>
            @endrole


            

            </div>
            

           <!--  ROLE-BASED GUIDE CAROUSELS -->
                @role('Super_Admin')
                <div x-data="guideCarousel('Super_Admin')">
                    @include('components.guide-carousel')
                </div>
                @endrole

                @role('Purchaser')
                <div x-data="guideCarousel('Purchaser')">
                    @include('components.guide-carousel')
                </div>
                @endrole

                @role('BAC_Secretary')
                <div x-data="guideCarousel('BAC_Secretary')">
                    @include('components.guide-carousel')
                </div>
                @endrole
                @role('Supplier')
                <div x-data="guideCarousel('Supplier')">
                    @include('components.guide-carousel')
                </div>
                @endrole
        
        </main>
        <script>
            function guideCarousel(role) {
                const slidesByRole = {
                    Super_Admin: [
                        {
                            image: '/icon/image1.PNG',
                            title: 'Dashboard Overview',
                            description: 'Quickly access your system overview — view Admin, Purchaser, and Supplier counts, and check announcements in one place.'
                        },
                        {
                            image: '/icon/image2.PNG',
                            title: 'User Management',
                            description: 'Manage user accounts efficiently. Create new users, view archived ones, and filter by account type for easier access.'
                        },
                        {
                            image: '/icon/image3.PNG',
                            title: 'System Controls',
                            description: 'Monitor activities, adjust permissions, and ensure smooth system operations through the administrative tools.'
                        }
                    ],
            
                 Purchaser: [
                            {
                                image: '/icon/purchaserimage1.PNG',
                                title: 'Dashboard',
                                description: 'Access an overview of procurement activities, including pending requests, approved purchase orders, and budget updates — all in one place for quick monitoring.'
                            },
                            {
                                image: '/icon/purchaserimage2.PNG',
                                title: 'Purchase Order',
                                description: 'Create, view, and manage Purchase Orders seamlessly. Track supplier details, item lists, and order statuses to ensure smooth procurement flow.'
                            },
                            {
                                image: '/icon/purchaserimage3.PNG',
                                title: 'List of PR Entries',
                                description: 'Review all submitted Purchase Requests (PRs) with their corresponding details, approval statuses, and submission timelines for better tracking and management.'
                            },
                            {
                                image: '/icon/purchaserimage4.PNG',
                                title: 'PR Details',
                                description: 'Drill down into specific Purchase Request information — including item specifications, budget allocations, and justification notes — for review or processing.'
                            },
                            {
                                image: '/icon/purchaserimage5.PNG',
                                title: 'List of PPMP Entries',
                                description: 'View and manage the Project Procurement Management Plan (PPMP) entries to ensure alignment with annual procurement goals and approved budgets.'
                            }
                        ],
                                    
                        BAC_Secretary: [
                            {
                                image: '/icon/bacsec1.PNG',
                                title: 'BACSec Dashboard',
                                description: 'Get an overview of active procurements, open bids, RFQs, and registered suppliers to efficiently manage ongoing procurement activities.'
                            },
                            {
                                image: '/icon/bacsec2.PNG',
                                title: 'Purchase Request',
                                description: 'Review and track purchase requests submitted for approval, including requester details, purpose, and current status.'
                            },
                            {
                                image: '/icon/bacsec3.PNG',
                                title: 'Mode of Procurement',
                                description: 'Monitor and manage procurement modes such as bidding or quotation, ensuring each request follows the appropriate process and status.'
                            },
                            {
                                image: '/icon/bacsec4.PNG',
                                title: 'Requested Purchaser',
                                description: 'View detailed item requests from purchasers, including quantities, unit costs, and total estimated amounts for transparency and validation.'
                            },
                            {
                                image: '/icon/bacsec5.PNG',
                                title: 'Procurement Workflow',
                                description: 'Track the entire procurement lifecycle — from invitation and evaluation to contract awarding — ensuring smooth and transparent processing.'
                            },
                            {
                                image: '/icon/bacsec6.PNG',
                                title: 'Notice of Award',
                                description: 'Access and review awarded contracts, including reference numbers, procurement types, and awardee details for record-keeping and monitoring.'
                            }
                        ],
                        Supplier: [
                            {
                                image: '/icon/supplier1.PNG',
                                title: 'Supplier Dashboard',
                                description: 'Access an overview of your procurement activities, including active procurements, recent updates, and important announcements to stay informed and organized.'
                            },
                            {
                                image: '/icon/supplier2.PNG',
                                title: 'Invitations',
                                description: 'View all active procurement invitations sent by the BAC or Purchaser. Check details such as reference number, title, and procurement type, and decide on your participation.'
                            },
                            {
                                image: '/icon/supplier3.PNG',
                                title: 'Proposal Submission',
                                description: 'Submit your proposals or quotations for invited procurements. Ensure all required information and documents are provided for proper evaluation and consideration.'
                            },
                            {
                                image: '/icon/supplier4.PNG',
                                title: 'Notice of Award',
                                description: 'Track the results of procurement processes and view awarded contracts. Check if your submitted proposals have been selected and monitor award status updates.'
                            }
                        ]
                };
            
                return {
                    show: true,
                    current: 0,
                    slides: slidesByRole[role] || [],

                    init() {
                        let startX = 0, endX = 0;
                        window.addEventListener('mousedown', e => startX = e.clientX);
                        window.addEventListener('mouseup', e => {
                            endX = e.clientX;
                            if (startX - endX > 100) this.next();
                            if (endX - startX > 100) this.prev();
                        });
                    },

                    next() {
                        if (this.current < this.slides.length - 1) this.current++;
                        else this.closeGuide();
                    },

                    prev() {
                        this.current = (this.current - 1 + this.slides.length) % this.slides.length;
                    },

                    closeGuide() { 
                        this.show = false;

                        //  Smooth fade-out fix — reset after transition ends
                        setTimeout(() => {
                            this.current = 0; // optional reset
                        }, 300); // match your x-transition duration (300ms recommended)
                    }
                };

            }
            </script>
            
        
        
        
        
</div>
