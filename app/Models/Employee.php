<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'role',
        'salary_type',
        'daily_salary',
        'monthly_salary',
    ];

    protected $casts = [
        'daily_salary' => 'decimal:2',
        'monthly_salary' => 'decimal:2',
    ];

    public function payments(): HasMany
    {
        return $this->hasMany(EmployeePayment::class);
    }

    public function advances(): HasMany
    {
        return $this->hasMany(EmployeeAdvance::class);
    }

    /**
     * Total salary paid (sum of all payments)
     */
    public function totalPaid(): float
    {
        return $this->payments()->sum('amount');
    }

    /**
     * Total advances taken (sum of all advances)
     */
    public function totalAdvances(): float
    {
        return $this->advances()->sum('amount');
    }

    /**
     * Net balance:
     * Positive  → Company owes employee (salary paid > advances)
     * Negative → Employee owes company (advances > salary paid)
     */
    public function netBalance(): float
    {
        return $this->totalPaid() - $this->totalAdvances();
    }
}