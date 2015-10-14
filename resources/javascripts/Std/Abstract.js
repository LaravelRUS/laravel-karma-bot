import Serialize from "/Reflection/Serialize";

/**
 * Sets abstract method or property
 *
 * @param context
 * @param name
 * @param descriptor
 * @returns {*}
 */
export default function Abstract(context, name, descriptor) {
    var declarationName = Serialize.objectToString(context) + '.' + descriptor.value.name;

    descriptor.value = function() {
        var contextName = Serialize.objectToString(this) + ' class';
        throw new ReferenceError('Can not call an abstract method ' + declarationName + ' from ' + contextName);
    };

    return descriptor;
}
