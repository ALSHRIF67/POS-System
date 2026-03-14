<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeePayment;
use App\Models\EmployeeAdvance;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class EmployeeController extends Controller
{
    /**
     * Display a listing of employees with statistics.
     */
    public function index(): View
    {
        $employees = Employee::with(['payments', 'advances'])->get();

        // Dashboard statistics
        $totalEmployees = $employees->count();
        $totalSalaryPaid = $employees->sum(fn($e) => $e->totalPaid());
        $totalAdvances = $employees->sum(fn($e) => $e->totalAdvances());
        $totalOwedToEmployees = $employees->sum(fn($e) => max($e->netBalance(), 0)); // what we owe them
        $totalOwedByEmployees = $employees->sum(fn($e) => max(-$e->netBalance(), 0)); // what they owe us

        return view('employees.index', compact(
            'employees',
            'totalEmployees',
            'totalSalaryPaid',
            'totalAdvances',
            'totalOwedToEmployees',
            'totalOwedByEmployees'
        ));
    }

    /**
     * Show the form for creating a new employee.
     */
    public function create(): View
    {
        return view('employees.create');
    }

    /**
     * Store a newly created employee.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'phone'          => 'nullable|string|max:20',
            'role'           => 'required|string|in:Chef,Waiter,Cashier,Juice Maker,Cleaner,Accountant,Manager',
            'salary_type'    => 'required|in:daily,monthly',
            'daily_salary'   => 'nullable|required_if:salary_type,daily|numeric|min:0',
            'monthly_salary' => 'nullable|required_if:salary_type,monthly|numeric|min:0',
        ]);

        Employee::create($validated);

        return redirect()->route('employees.index')
            ->with('success', 'Employee created successfully.');
    }

    /**
     * Display the specified employee.
     */
    public function show(Employee $employee): View
    {
        $employee->load(['payments', 'advances']);
        return view('employees.show', compact('employee'));
    }

    /**
     * Show the form for editing the employee.
     */
    public function edit(Employee $employee): View
    {
        return view('employees.edit', compact('employee'));
    }

    /**
     * Update the employee.
     */
    public function update(Request $request, Employee $employee): RedirectResponse
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'phone'          => 'nullable|string|max:20',
            'role'           => 'required|string|in:Chef,Waiter,Cashier,Juice Maker,Cleaner,Accountant,Manager',
            'salary_type'    => 'required|in:daily,monthly',
            'daily_salary'   => 'nullable|required_if:salary_type,daily|numeric|min:0',
            'monthly_salary' => 'nullable|required_if:salary_type,monthly|numeric|min:0',
        ]);

        $employee->update($validated);

        return redirect()->route('employees.index')
            ->with('success', 'Employee updated successfully.');
    }

    /**
     * Remove the employee.
     */
    public function destroy(Employee $employee): RedirectResponse
    {
        // Optionally check for related records before deletion
        $employee->delete();

        return redirect()->route('employees.index')
            ->with('success', 'Employee deleted successfully.');
    }

    /**
     * Show form to record a salary payment.
     */
    public function createPayment(Employee $employee): View
    {
        return view('employees.payment', compact('employee'));
    }

    /**
     * Store a salary payment.
     */
    public function storePayment(Request $request, Employee $employee): RedirectResponse
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'notes'  => 'nullable|string|max:500',
        ]);

        $employee->payments()->create($validated);

        return redirect()->route('employees.show', $employee)
            ->with('success', 'Payment recorded successfully.');
    }

    /**
     * Show form to record an advance.
     */
    public function createAdvance(Employee $employee): View
    {
        return view('employees.advance', compact('employee'));
    }

    /**
     * Store an advance.
     */
    public function storeAdvance(Request $request, Employee $employee): RedirectResponse
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'notes'  => 'nullable|string|max:500',
        ]);

        $employee->advances()->create($validated);

        return redirect()->route('employees.show', $employee)
            ->with('success', 'Advance recorded successfully.');
    }

    /**
     * Salary report (overview for all employees).
     */
    public function salaryReport(): View
    {
        $employees = Employee::with(['payments', 'advances'])->get()->map(function ($employee) {
            return [
                'name'          => $employee->name,
                'role'          => $employee->role,
                'total_paid'    => $employee->totalPaid(),
                'total_advances'=> $employee->totalAdvances(),
                'net_balance'   => $employee->netBalance(),
            ];
        });

        $totalPaid = $employees->sum('total_paid');
        $totalAdvances = $employees->sum('total_advances');
        $totalNet = $totalPaid - $totalAdvances;

        return view('employees.report', compact('employees', 'totalPaid', 'totalAdvances', 'totalNet'));
    }
}