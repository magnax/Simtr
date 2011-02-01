<?php
class Predis_ClientOptionsKeyDistribution implements Predis_IClientOptionsHandler {
    public function validate($option, $value) {
        if ($value instanceof Predis_Distribution_IDistributionStrategy) {
            return $value;
        }
        if (is_string($value)) {
            $valueReflection = new ReflectionClass($value);
            if ($valueReflection->isSubclassOf('Predis_Distribution_IDistributionStrategy')) {
                return new $value;
            }
        }
        throw new InvalidArgumentException("Invalid value for option $option");
    }

    public function getDefault() {
        return new Predis_Distribution_HashRing();
    }
}
?>
