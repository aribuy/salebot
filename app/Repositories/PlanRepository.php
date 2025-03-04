<?php

namespace App\Repositories;

use App\Models\Plan;
use App\Models\PlanCredential;
use App\Traits\PaymentTrait;

class PlanRepository
{
    use PaymentTrait;

    private $model;

    public function __construct(Plan $model)
    {
        $this->model = $model;
    }


    public function all($data = [])
    {
        return$this->model->orderBy('price')->get();
    }
   
    
    public function findActive($id)
    {
        return$this->model->active()->findOrfail($id);
    }

    public function store($request)
    {
        $request['price'] = priceFormatUpdate($request['price'], setting('default_currency'));
        $request['color'] = $request['color'] ?? '#E0E8F9';
        // Check if 'is_free' is set and handle accordingly
        if ($request['is_free'] && $request['is_free'] == 1) {
            $request['price']   = 0;
            $request['is_free'] = 1;
        } else {
            $request['is_free'] = 0;
        }
        $package          =$this->model->create($request);
        $request['id']    = $package->id;
        $this->createStripePlan($request);
        $this->createPaypalPlan($request);
        $this->createPaddlePlan($request);
        $this->createRazorPayPlan($request);

        return $package;
    }

    public function update($request, $id)
    {

        $request['price'] = priceFormatUpdate($request['price'], setting('default_currency'));
        $request['color'] = $request['color'] ?? '#E0E8F9';
        $package          =$this->model->findOrfail($id);
        $request['id']    = $package->id;
        $this->createStripePlan($request);
        $this->createPaypalPlan($request);
        $this->createPaddlePlan($request);
        $this->createRazorPayPlan($request);
        // Check if 'is_free' is set and handle accordingly
        if ($request['is_free'] == 1) {
            $request['price']   = 0;
            $request['is_free'] = 1;
        } else {
            $request['is_free'] = 0;
        }

        $package->update($request);

        return $package;
    }

    public function find($id)
    {
        return$this->model->find($id);
    }

    public function status($data)
    {
        $key         =$this->model->findOrfail($data['id']);
        $key->status = $data['status'];

        return $key->save();
    }

    public function destroy($id)
    {
        PlanCredential::where('plan_id', $id)->delete();

        return$this->model->destroy($id);
    }

    public function createStripePlan($data)
    {
        if (arrayCheck('stripe', $data)) {
            $package = PlanCredential::where('plan_id', $data['id'])->where('title', 'stripe')->first();

            if ($package) {
                $package->value = $data['stripe'];
                $package->save();
            } else {
                PlanCredential::create([
                    'plan_id' => $data['id'],
                    'title'   => 'stripe',
                    'value'   => $data['stripe'],
                ]);
            }
        }

        return null;
    }

    public function createPaypalPlan($data)
    {
        if (arrayCheck('paypal', $data)) {
            $package = PlanCredential::where('plan_id', $data['id'])->where('title', 'paypal')->first();

            if ($package) {
                $package->value = $data['paypal'];
                $package->save();
            } else {
                PlanCredential::create([
                    'plan_id' => $data['id'],
                    'title'   => 'paypal',
                    'value'   => $data['paypal'],
                ]);
            }
        }

        return null;
    }

    public function createPaddlePlan($data)
    {
        if (arrayCheck('paddle', $data)) {
            $package = PlanCredential::where('plan_id', $data['id'])->where('title', 'paddle')->first();

            if ($package) {
                $package->value = $data['paddle'];
                $package->save();
            } else {
                PlanCredential::create([
                    'plan_id' => $data['id'],
                    'title'   => 'paddle',
                    'value'   => $data['paddle'],
                ]);
            }
        }

        return null;
    }
    public function createRazorPayPlan($data)
    {
        if (arrayCheck('razor_pay', $data)) {
            $package = PlanCredential::where('plan_id', $data['id'])->where('title', 'razor_pay')->first();
            if ($package) {
                $package->value = $data['paddle'];
                $package->save();
            } else {
                PlanCredential::create([
                    'plan_id' => $data['id'],
                    'title'   => 'razor_pay',
                    'value'   => $data['razor_pay'],
                ]);
            }
        }

        return null;
    }

    public function activePlans($data = [], $billing_period = 'all')
    {
        if ($billing_period == 'all') {
            return$this->model->where('status', '1')->orderBy('price', 'ASC')->get();
        } else {
            if ($billing_period == 'daily') {
                return$this->model->where('status', '1')->where('billing_period', 'daily')->get();
            } elseif ($billing_period == 'weekly') {
                return$this->model->where('status', '1')->where('billing_period', 'weekly')->get();
            } elseif ($billing_period == 'monthly') {
                return$this->model->where('status', '1')->where('billing_period', 'monthly')->get();
            } elseif ($billing_period == 'quarterly') {
                return$this->model->where('status', '1')->where('billing_period', 'quarterly')->get();
            } elseif ($billing_period == 'half_yearly') {
                return$this->model->where('status', '1')->where('billing_period', 'half_yearly')->get();
            } elseif ($billing_period == 'yearly') {
                return$this->model->where('status', '1')->where('billing_period', 'yearly')->get();
            } else {
                return$this->model->where('status', '1')->orderBy('price', 'ASC')->get();
            }
        }
    }

    public function getPGCredential($plan_id, $title)
    {
        return PlanCredential::where('plan_id', $plan_id)->where('title', $title)->value('value');
    }

    public function bestSellingPlan()
    {
        return$this->model->withCount('subscriptions')->orderByDesc('subscriptions_count')->where('status', 1)->take(5)->get();
    }
}
