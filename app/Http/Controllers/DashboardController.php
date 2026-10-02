<?php

namespace App\Http\Controllers;

use App\Enums\UserType;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Carbon;

class DashboardController extends BaseController
{
    public function index()
    {
        $this->data['pageTitle'] = __('Dashboard');
        
        if (auth()->user()->type === UserType::EMPLOYEE) {
            return view('pages.employees.dashboard', $this->data);
        }
        
        $projects = null;
        $recentProjects = null;
        
        // Error Fix: Module Facade-nu pakaram direct class_exists upayogikkunnu
        $isProjectModuleEnabled = class_exists('Modules\\Project\\Models\\Project');
        
        if ($isProjectModuleEnabled) {
            $projectModel = 'Modules\\Project\\Models\\Project';
            $projects = $projectModel::get();
            $recentProjects = $projectModel::whereMonth('created_at', Carbon::today())->get();
        }
        
        $clients = User::where('type', UserType::CLIENT)->get();
        $thisMonthClients = User::where('type', UserType::CLIENT)->whereMonth('created_at', Carbon::today())->get();
        
        $employees = User::where('type', UserType::EMPLOYEE)->get();
        $tickets = Ticket::get();

        // Error Fix: Module Facade-nu pakaram direct class_exists upayogikkunnu
        $isSalesModuleActive = class_exists('Modules\\Sales\\Models\\Expense');
        
        if ($isSalesModuleActive) {
            
            $expenseModel = 'Modules\\Sales\\Models\\Expense';
            $estimateModel = 'Modules\\Sales\\Models\\Estimate';
            $invoiceModel = 'Modules\\Sales\\Models\\Invoice';
            $budgetModel = 'Modules\\Accounting\\Models\\Budget';

            $this->data['thisMonthExpenses'] = $expenseModel::whereMonth('created_at', Carbon::now())->sum('amount');
            $this->data['prevMonthExpenses'] = $expenseModel::whereMonth('created_at', Carbon::now()->subMonth())->sum('amount');

            $this->data['thisMonthEstimates'] = $estimateModel::whereMonth('created_at', Carbon::now())->sum('grand_total');
            $this->data['prevMonthEstimates'] = $estimateModel::whereMonth('created_at', Carbon::now()->subMonth())->sum('grand_total');

            $this->data['thisMonthInvoices'] = $invoiceModel::whereMonth('created_at', Carbon::now())->sum('grand_total');
            $this->data['prevMonthInvoices'] = $invoiceModel::whereMonth('created_at', Carbon::now()->subMonth())->sum('grand_total');
            $this->data['invoices'] = $invoiceModel::get();

            $this->data['thisMonthInvoiceList'] = $invoiceModel::whereMonth('created_at', Carbon::now())->get();
            $this->data['thisMonthPaidInvoiceList'] = $invoiceModel::whereMonth('created_at', Carbon::now())->where('status', '2')->get();

            $month = 1;
            $expense_collection = collect();
            $budget_collection = collect();
            $invoice_collection = collect();
            $estimates_collection = collect();
            
            while ($month <= 12) {
                $expense_collection->push($expenseModel::whereMonth('created_at', $month)->get());
                
                if (class_exists($budgetModel)) {
                    $budget_collection->push($budgetModel::whereMonth('created_at', $month)->get());
                }
                
                $invoice_collection->push($invoiceModel::whereMonth('created_at', $month)->get());
                $estimates_collection->push($estimateModel::whereMonth('created_at', $month)->get());
                $month += 1;
            }
            
            $this->data['monthly_expense'] = $expense_collection;
            $this->data['budget_collection'] = $budget_collection;
            $this->data['invoice_collection'] = $invoice_collection;
            $this->data['estimates_collection'] = $estimates_collection;
        }

        $budgets = null;
        
        // Error Fix: Module Facade-nu pakaram direct class_exists upayogikkunnu
        $isAccountingModuleActive = class_exists('Modules\\Accounting\\Models\\Budget');

        if ($isAccountingModuleActive) {
            $budgetModel = 'Modules\\Accounting\\Models\\Budget';
            $budgets = $budgetModel::get();
        }

        // attendances
        $absentees = User::where('type', UserType::EMPLOYEE)->whereDoesntHave('attendances', function ($query) {
            return $query->whereDay('created_at', Carbon::today())->take(1);
        })->get();

        $this->data['absentees'] = $absentees;
        
        $this->data['thisMonthTotalEmployees'] = User::where('type', UserType::EMPLOYEE)->whereMonth('created_at', Carbon::now())->count() ?? 0;
        $this->data['prevMonthTotalEmployees'] = User::where('type', UserType::EMPLOYEE)->whereMonth('created_at', Carbon::now()->subMonth())->count() ?? 0;
        
        $this->data['clients'] = (! empty($clients) && $clients->count() > 0) ? $clients : null;
        $this->data['thisMonthClients'] = $thisMonthClients;
        $this->data['employees'] = (! empty($employees) && $employees->count() > 0) ? $employees : null;
        $this->data['tickets'] = (! empty($tickets) && $tickets->count() > 0) ? $tickets : null;
        $this->data['projects'] = $projects;
        $this->data['recentProjects'] = $recentProjects;
        $this->data['isProjectModuleEnabled'] = $isProjectModuleEnabled;
        $this->data['isSalesModuleActive'] = $isSalesModuleActive;
        $this->data['isAccountingModuleActive'] = $isAccountingModuleActive;

        return view('pages.dashboard', $this->data);
    }
}