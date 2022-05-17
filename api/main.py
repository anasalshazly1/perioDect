import os
import numpy as np
import cv2
import glob
from keras.applications.vgg19 import VGG19
from tensorflow.keras.layers import Dense
from tensorflow.keras.models import Model
from keras.layers import GlobalMaxPooling2D

from flask import Flask, request

app = Flask(__name__)

if __name__ == '__main__':

    image_size = 128, 128
    base_model = VGG19(include_top=False)
    print('----------------------------- ', len(base_model.layers), '---------------------------')
    x = base_model.output
    x = GlobalMaxPooling2D()(x)
    x = Dense(1024, activation='relu')(x)
    predictions = Dense(2, activation='softmax')(x)
    model = Model(inputs=base_model.input, outputs=predictions)
    for layer in base_model.layers[0:20]:
        layer.trainable = False
    for layer in base_model.layers[20:]:
        layer.trainable = True

    weights = []
    weights = glob.glob("model_weights/*")
    weights.sort(reverse=True)
    print(weights)


    def load_existing_model_weights():
        from pathlib import Path
        user_home = str(Path.home())
        model.load_weights(weights[0])
        return model


    model = load_existing_model_weights()


    def load_image(file):
        try:
            print(file)
            img = cv2.imread(file)
            img = cv2.cvtColor(img, cv2.COLOR_BGR2RGB)
            img = cv2.resize(img, image_size)
            return img
        except:
            print("None")


    @app.route('/predict', methods=['GET'])
    def classify_teeth():
        if (model):
            try:
                file_loc = os.path.join("../public/avatars", request.args['name'])
                test_image = load_image(file_loc)
                img = np.asarray(test_image)
                img = img / 255.0
                img = img.reshape(-1, 128, 128, 3)
                prediction = model.predict(img)

                if (np.argmax(model.predict(img), axis=-1) == [1]):
                    return "Periodontal"
                elif (np.argmax(model.predict(img), axis=-1) == [0]):
                    return "Non-Periodontal"
            except:
                return ('No model here to use')


    cls = classify_teeth()

    print(cls)

    app.run(debug=True)